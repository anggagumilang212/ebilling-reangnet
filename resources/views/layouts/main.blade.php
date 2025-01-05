<div class="custom-template">
    <div class="title">Settings</div>
    <div class="custom-content">
        <div class="switcher">
            <div class="switch-block">
                <h4>Logo Header</h4>
                <div class="btnSwitch">
                    <button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
                    <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                    <br />
                    <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                    <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
                </div>
            </div>
            <div class="switch-block">
                <h4>Navbar Header</h4>
                <div class="btnSwitch">
                    <button type="button" class="changeTopBarColor" data-color="dark"></button>
                    <button type="button" class="changeTopBarColor" data-color="blue"></button>
                    <button type="button" class="changeTopBarColor" data-color="purple"></button>
                    <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                    <button type="button" class="changeTopBarColor" data-color="green"></button>
                    <button type="button" class="changeTopBarColor" data-color="orange"></button>
                    <button type="button" class="changeTopBarColor" data-color="red"></button>
                    <button type="button" class="changeTopBarColor" data-color="white"></button>
                    <br />
                    <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                    <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
                    <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                    <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                    <button type="button" class="changeTopBarColor" data-color="green2"></button>
                    <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                    <button type="button" class="changeTopBarColor" data-color="red2"></button>
                </div>
            </div>
            <div class="switch-block">
                <h4>Sidebar</h4>
                <div class="btnSwitch">
                    <button type="button" class="selected changeSideBarColor" data-color="white"></button>
                    <button type="button" class="changeSideBarColor" data-color="dark"></button>
                    <button type="button" class="changeSideBarColor" data-color="dark2"></button>
                </div>
            </div>
            <div class="switch-block">
                <h4>Background</h4>
                <div class="btnSwitch">
                    <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
                    <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
                    <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
                    <button type="button" class="changeBackgroundColor" data-color="dark"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-toggle">
        <i class="flaticon-settings"></i>
    </div>
</div>

<script>
    // Fungsi untuk menyimpan pengaturan ke localStorage
    function saveSettings(type, color) {
        localStorage.setItem(`theme_${type}`, color);
    }

    // Fungsi untuk mendapatkan pengaturan dari localStorage
    function getSettings(type) {
        return localStorage.getItem(`theme_${type}`);
    }

    // Fungsi untuk menerapkan pengaturan yang tersimpan
    function applySettings() {
        const types = ['LogoHeader', 'TopBar', 'SideBar', 'Background'];

        types.forEach(type => {
            const savedColor = getSettings(type);
            if (savedColor) {
                // Hapus selected class dari semua button untuk tipe ini
                document.querySelectorAll(`.change${type}Color`).forEach(btn => {
                    btn.classList.remove('selected');
                });

                // Tambahkan selected class ke button yang sesuai
                const targetButton = document.querySelector(`.change${type}Color[data-color="${savedColor}"]`);
                if (targetButton) {
                    targetButton.classList.add('selected');

                    // Trigger perubahan warna sesuai dengan implementasi yang ada
                    // Sesuaikan dengan fungsi yang sudah ada di template Anda
                    if (type === 'LogoHeader') {
                        // Implementasi perubahan warna logo header
                        document.querySelector('.logo-header').setAttribute('data-background-color',
                        savedColor);
                    } else if (type === 'TopBar') {
                        // Implementasi perubahan warna navbar
                        document.querySelector('.navbar-header').setAttribute('data-background-color',
                            savedColor);
                    } else if (type === 'SideBar') {
                        // Implementasi perubahan warna sidebar
                        document.querySelector('.sidebar').setAttribute('data-background-color', savedColor);
                    } else if (type === 'Background') {
                        // Implementasi perubahan warna background
                        document.body.setAttribute('data-background-color', savedColor);
                    }
                }
            }
        });
    }

    // Event listener untuk setiap button
    document.addEventListener('DOMContentLoaded', function() {
        // Terapkan pengaturan yang tersimpan saat halaman dimuat
        applySettings();

        // Event listener untuk Logo Header
        document.querySelectorAll('.changeLogoHeaderColor').forEach(button => {
            button.addEventListener('click', function() {
                const color = this.getAttribute('data-color');
                saveSettings('LogoHeader', color);
            });
        });

        // Event listener untuk Navbar
        document.querySelectorAll('.changeTopBarColor').forEach(button => {
            button.addEventListener('click', function() {
                const color = this.getAttribute('data-color');
                saveSettings('TopBar', color);
            });
        });

        // Event listener untuk Sidebar
        document.querySelectorAll('.changeSideBarColor').forEach(button => {
            button.addEventListener('click', function() {
                const color = this.getAttribute('data-color');
                saveSettings('SideBar', color);
            });
        });

        // Event listener untuk Background
        document.querySelectorAll('.changeBackgroundColor').forEach(button => {
            button.addEventListener('click', function() {
                const color = this.getAttribute('data-color');
                saveSettings('Background', color);
            });
        });
    });
</script>
