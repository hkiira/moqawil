<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="fr">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Meta - <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"/>
    <?= $this->Html->css('/metronic/vendors/keenicons/styles.bundle.css') ?>
    <?= $this->Html->css('/metronic/css/core.bundle.css') ?>
    <?= $this->Html->css('/metronic/css/styles.css') ?>
    <?= $this->Html->css('/assets/css/style.bundle.css') ?>
    
    <!-- jQuery & DataTables CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
    <?= $this->Html->css('/css/dashboard-custom.css') ?>
    
    <script>
        const defaultThemeMode = 'light';
        let themeMode;
        if (document.documentElement) {
            if (localStorage.getItem('kt-theme')) {
                themeMode = localStorage.getItem('kt-theme');
            } else if (document.documentElement.hasAttribute('data-kt-theme-mode')) {
                themeMode = document.documentElement.getAttribute('data-kt-theme-mode');
            } else {
                themeMode = defaultThemeMode;
            }
            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.classList.add(themeMode);
        }
    </script>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css_top') ?>
    <?= $this->fetch('script_top') ?>
</head>
<body class="antialiased flex h-full text-base text-foreground bg-background demo1 kt-sidebar-fixed kt-header-fixed">
    <div class="flex grow">
        <!-- Metronic v9 Sidebar -->
        <div class="kt-sidebar bg-background border-e border-e-border fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0" id="sidebar">
            <div class="kt-sidebar-header hidden lg:flex items-center relative justify-between px-3 lg:px-6 shrink-0 py-4 border-b border-border" id="sidebar_header">
                <div class="kt-sidebar-logo min-w-0">
                    <a href="<?= $this->Url->build('/'); ?>" class="flex items-center gap-2">
                        <span class="font-bold text-primary text-xl tracking-wider">META SALES</span>
                    </a>
                </div>
            </div>
            <?= $this->element('general/asidemenu') ?>
        </div>

        <!-- Metronic v9 Wrapper & Content Area -->
        <div class="kt-wrapper flex flex-col flex-row-fluid" id="kt_wrapper">
            <?= $this->element('general/header') ?>

            <main class="kt-content flex flex-col flex-column-fluid p-6 lg:p-8" id="kt_content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </main>

            <?= $this->element('general/footer') ?>
        </div>
    </div>

    <!-- DataTables JS & Core Theme Scripts -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <?= $this->Html->script('/metronic/js/core.bundle.js') ?>

    <?= $this->fetch('css_bottom') ?>
    <?= $this->fetch('script_bottom') ?>
    <?= $this->fetch('script') ?>
</body>
</html>
