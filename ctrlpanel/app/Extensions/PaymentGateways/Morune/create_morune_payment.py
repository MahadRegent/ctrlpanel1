import json
import requests
import uuid
import sys

# --- Вспомогательные функции для взаимодействия с API Morune ---

def get_payment_tariffs(shop_id, api_key):
  """
  Получает список доступных платежных тарифов для магазина.

  Args:
    shop_id (str): Идентификатор кассы.
    api_key (str): Секретный ключ кассы.

  Returns:
    dict: JSON-ответ от API с информацией о тарифах.
  """
  session = requests.Session()
  session.headers['Accept'] = 'application/json'
  session.headers['x-api-key'] = api_key
  try:
    response = session.get(f'https://api.morune.com/shops/{shop_id}/payment-tariffs')
    response.raise_for_status() # Вызывает исключение для ошибок HTTP (4xx или 5xx)
    return response.json()
  except requests.exceptions.RequestException as e:
    return {"status": 500, "status_check": False, "error": f"Ошибка соединения при получении тарифов: {e}"}

def create_invoice(api_key, invoice_data):
  """
  Создает инвойс (платеж) через API Morune.

  Args:
    api_key (str): Секретный ключ кассы.
    invoice_data (dict): Словарь с данными для создания инвойса.

  Returns:
    dict: JSON-ответ от API.
  """
  session = requests.Session()
  session.headers['Accept'] = 'application/json'
  session.headers['Content-Type'] = 'application/json'
  session.headers['x-api-key'] = api_key
  try:
    response = session.post('https://api.morune.com/invoice/create', data=json.dumps(invoice_data))
    response.raise_for_status() # Вызывает исключение для ошибок HTTP (4xx или 5xx)
    return response.json()
  except requests.exceptions.RequestException as e:
    return {"status": 500, "status_check": False, "error": f"Ошибка соединения при создании инвойса: {e}"}

def create_morune_invoice_link(
    shop_id: str,
    api_key: str,
    amount: float,
    currency: str,
    comment: str,
    order_id: str, # order_id теперь обязателен, так как передается из PHP
    success_url: str,
    fail_url: str,
    hook_url: str,
    expire: int = 60 # Срок действия инвойса в минутах
) -> dict:
  """
  Создает платежный инвойс Morune и возвращает структурированный ответ для PHP.

  Args:
    shop_id (str): Идентификатор кассы.
    api_key (str): Секретный ключ кассы.
    amount (float): Сумма платежа.
    currency (str): Валюта платежа (например, "RUB").
    comment (str): Комментарий к платежу.
    order_id (str): Уникальный идентификатор заказа в вашей системе.
    success_url (str): URL для перенаправления после успешной оплаты.
    fail_url (str): URL для перенаправления после неудачной оплаты.
    hook_url (str): URL для вебхука (уведомления о статусе платежа).
    expire (int, optional): Срок действия инвойса в минутах. По умолчанию 60.

  Returns:
    dict: JSON-совместимый словарь с информацией об инвойсе или ошибке.
  """
  tariffs_response = get_payment_tariffs(shop_id, api_key)

  available_services = []
  if tariffs_response.get('status') == 200 and tariffs_response.get('status_check') is True:
    tariffs = tariffs_response.get('data', {}).get('tariffs', [])
    for tariff in tariffs:
      if tariff.get('status') == 'enabled':
        service_code = tariff.get('service')
        available_services.append(service_code)
    if not available_services:
      return {"status": 500, "status_check": False, "error": "Нет активных методов оплаты. Проверьте настройки в личном кабинете."}
  else:
    error_message = tariffs_response.get('error', 'Неизвестная ошибка')
    return {"status": tariffs_response.get('status', 500), "status_check": False, "error": f"Ошибка при получении тарифов: {error_message}. Проверьте shopId и секретный ключ кассы."}

  invoice_data = {
      "amount": amount,
      "order_id": order_id,
      "currency": currency,
      "shop_id": shop_id,
      "comment": comment,
      "success_url": success_url,
      "fail_url": fail_url,
      "hook_url": hook_url,
      "expire": expire,
      "include_service": available_services
  }

  response = create_invoice(api_key, invoice_data)

  if response.get('status') == 200 and response.get('status_check') is True:
    return response
  else:
    error_message = response.get('error', 'Неизвестная ошибка.')
    status_code = response.get('status')
    return {"status": status_code, "status_check": False, "error": f"Ошибка при создании платежа: {error_message}. Полный ответ: {response}"}

# --- Основная логика для запуска скрипта ---
if __name__ == "__main__":
  # Если скрипт запускается с аргументами (из PHP), обрабатываем их
  if len(sys.argv) == 9: # Ожидаем 8 аргументов + имя скрипта
    try:
      shop_id = sys.argv[1]
      api_key = sys.argv[2]
      amount = float(sys.argv[3])
      currency = sys.argv[4]
      order_id = sys.argv[5]
      success_url = sys.argv[6]
      fail_url = sys.argv[7]
      hook_url = sys.argv[8]
      
      comment = f"Оплата заказа {order_id}"

      result = create_morune_invoice_link(
          shop_id=shop_id,
          api_key=api_key,
          amount=amount,
          currency=currency,
          comment=comment,
          order_id=order_id,
          success_url=success_url,
          fail_url=fail_url,
          hook_url=hook_url
      )
      print(json.dumps(result), file=sys.stdout)

    except ValueError:
      error_response = {
          "status": 400,
          "status_check": False,
          "error": "Неверный формат суммы. Сумма должна быть числом."
      }
      print(json.dumps(error_response), file=sys.stdout)
      sys.exit(1)
    except Exception as e:
      error_response = {
          "status": 500,
          "status_check": False,
          "error": f"Непредвиденная ошибка в Python-скрипте: {e}"
      }
      print(json.dumps(error_response), file=sys.stdout)
      sys.exit(1)
  else:
    # Если скрипт запускается без аргументов (напрямую, для тестирования),
    # используем тестовые данные и выводим отладочные сообщения.
    # Это позволяет запускать скрипт для ручного тестирования без влияния на PHP.
    YOUR_API_KEY = '90110c5214aa3bf9dd57acfb19ff3d55ce178e19'
    YOUR_SHOP_ID = '7abcfdf7-c0d9-4ff6-885a-fbd27340f211'
    AMOUNT_TO_PAY = 100.00
    CURRENCY = "RUB"
    COMMENT = "Тестовый платеж со всеми доступными методами оплаты"
    YOUR_ORDER_ID = f"test_payment_{uuid.uuid4()}" # Генерируем новый order_id для теста

    print("Запуск скрипта для создания тестового платежа (без аргументов)...")
    payment_link_result = create_morune_invoice_link(
        shop_id=YOUR_SHOP_ID,
        api_key=YOUR_API_KEY,
        amount=AMOUNT_TO_PAY,
        currency=CURRENCY,
        comment=COMMENT,
        order_id=YOUR_ORDER_ID,
        success_url="https://example.com/payment/success",
        fail_url="https://example.com/payment/fail",
        hook_url="https://example.com/payment/webhook"
    )

    if payment_link_result.get('status') == 200:
      print("\n--- Платеж успешно создан! (Тестовый запуск) ---")
      print(f"ID инвойса: {payment_link_result.get('data', {}).get('id')}")
      print(f"**Ссылка на оплату:** {payment_link_result.get('data', {}).get('url')}")
      print(f"Сумма: {payment_link_result.get('data', {}).get('amount')} {payment_link_result.get('data', {}).get('currency')}")
      print(f"Срок действия до: {payment_link_result.get('data', {}).get('expired')}")
      print("\nПерейдите по ссылке выше, чтобы завершить тестовый платеж и увидеть все доступные методы.")
    else:
      print(f"\n--- Ошибка при создании платежа (Тестовый запуск): ---")
      print(f"Сообщение: {payment_link_result.get('error')}")
      print(f"Полный ответ: {payment_link_result}")

