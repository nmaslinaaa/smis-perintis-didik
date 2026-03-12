<!-- Footer Content -->
<footer style="text-align: center; padding: 0px 105px; width: 100%; background: #fff; position: fixed; bottom: 0; left: 0; z-index: 100;">
    © 2025 SMIS Perintis Didik. All rights reserved.
</footer>

<!-- JavaScript for sidebar dropdown -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.dropdown-toggle');

        toggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                const parentLi = this.closest('.has-submenu');
                parentLi.classList.toggle('open');

                // Close other submenus
                document.querySelectorAll('.has-submenu').forEach(item => {
                    if (item !== parentLi) {
                        item.classList.remove('open');
                    }
                });
            });
        });
    });
</script>

<!-- Dropdown styling -->
<style>
    .submenu {
        display: none;
        list-style: none;
        padding-left: 20px;
        background-color: #2e2e2e;
    }

    .has-submenu.open .submenu {
        display: block;
    }

    .submenu li a {
        padding: 10px 20px;
        display: block;
        color: #ecf0f1;
        text-decoration: none;
    }

    .submenu li a:hover {
        background-color: #edc10c;
    }
</style>
