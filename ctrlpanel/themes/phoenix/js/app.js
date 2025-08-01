import Alpine from 'alpinejs';
import '../css/app.css';
import NiceSelect from 'nice-select2';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import '../css/sweetalert2.css';

Alpine.data('data', () => {
    const path = window.location.pathname;

    const management_routes = ['/admin/users', '/admin/servers', '/admin/products', '/admin/store', '/admin/vouchers', '/admin/partners', '/admin/coupons'];
    const other_routes = ['/admin/usefullinks', '/admin/legal'];
    const log_routes = ['/admin/payments', '/admin/activitylogs']

    return {
        dark: getThemeFromLocalStorage() === 'dark',
        toggleTheme() {
            this.dark = !this.dark
            setThemeToLocalStorage(this.dark ? 'dark' : 'light');
        },
        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen
        },
        closeSideMenu() {
            this.isSideMenuOpen = false
        },
        isNotificationsMenuOpen: false,
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false
        },
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false
        },
        isPagesMenuOpen: false,
        togglePagesMenu() {
            this.isPagesMenuOpen = !this.isPagesMenuOpen
        },
        isLogsMenuOpen: log_routes.find((route) => path.startsWith(route)) !== undefined,
        toggleLogsMenu() {
            this.isLogsMenuOpen = !this.isLogsMenuOpen
        },
        isManagementMenuOpen: management_routes.find((route) => path.startsWith(route)) !== undefined,
        toggleManagementMenu() {
            this.isManagementMenuOpen = !this.isManagementMenuOpen
        },
        isOtherMenuOpen: other_routes.find((route) => path.startsWith(route)) !== undefined,
        toggleOtherMenu() {
            this.isOtherMenuOpen = !this.isOtherMenuOpen
        },
        // Modal
        isModalOpen: false,
        trapCleanup: null,
        openModal() {
            this.isModalOpen = true
            this.trapCleanup = focusTrap(document.querySelector('#modal'))
        },
        closeModal() {
            this.isModalOpen = false
            this.trapCleanup()
        },
        openRedeemModal() {
            handleRedeemModal();
        },
    }
});

window.Alpine = Alpine;
window.Swal = Swal.mixin({
    customClass: {
        popup: "custom-swal-popup"
    },
    buttonsStyling: false
});

function copyText(textToCopy, title) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(textToCopy).then(() => {
            Swal.fire({
                icon: 'success',
                title: title,
                position: 'bottom-right',
                showConfirmButton: false,
                toast: true,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        })
    } else {
        console.log('Browser Not compatible');
        Swal.fire({
            icon: 'error',
            title: 'Browser Not compatible',
            position: 'bottom-right',
            showConfirmButton: false,
            toast: true,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    }
}

function convertIconsToIconify() {
    document.querySelectorAll('i.fas').forEach((elm) => {
        const classes = elm.attributes.getNamedItem("class").value.split(' ');
        const iconName = classes.find(cls => cls.startsWith('fa-'));
        if (iconName) {
            const icon = `fa-solid:${iconName.replace('fa-', '')}`;
            elm.setAttribute('icon', icon);
            elm.outerHTML = elm.outerHTML.replace(/^<i(.*)i>$/, "<iconify-icon$1iconify-icon>");
        }
    });
    document.querySelectorAll('i.far').forEach((elm) => {
        const classes = elm.attributes.getNamedItem("class").value.split(' ');
        const iconName = classes.find(cls => cls.startsWith('fa-'));
        if (iconName) {
            const icon = `fa-regular:${iconName.replace('fa-', '')}`;
            elm.setAttribute('icon', icon);
            elm.outerHTML = elm.outerHTML.replace(/^<i(.*)i>$/, "<iconify-icon$1iconify-icon>");
        }
    });
    document.querySelectorAll('i.fab').forEach((elm) => {
        const classes = elm.attributes.getNamedItem("class").value.split(' ');
        const iconName = classes.find(cls => cls.startsWith('fa-'));
        if (iconName) {
            const icon = `fa6-brands:${iconName.replace('fa-', '')}`;
            elm.setAttribute('icon', icon);
            elm.outerHTML = elm.outerHTML.replace(/^<i(.*)i>$/, "<iconify-icon$1iconify-icon>");
        }
    });
}

window.copyText = copyText;
window.convertIconsToIconify = convertIconsToIconify;
window.NiceSelect = NiceSelect;

// should be last
Alpine.start();
