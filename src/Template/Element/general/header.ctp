<header class="kt-header border-b border-border flex items-center justify-between px-6 py-4 bg-background" id="header">
    <div class="container-fluid flex items-center justify-between grow">
        <div class="flex items-center gap-3">
            <h1 class="text-lg font-semibold text-foreground"><?= $this->fetch('title') ?></h1>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 cursor-pointer" id="kt_quick_user_toggle">
                <span class="text-sm font-medium text-muted-foreground">Salut,</span>
                <span class="text-sm font-semibold text-foreground"><?= $this->request->getSession()->read('Auth.User.firstname'); ?></span>
                <div class="size-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                    <?= strtoupper(substr($this->request->getSession()->read('Auth.User.firstname') ?: 'U', 0, 1)) ?>
                </div>
            </div>
        </div>
    </div>
</header>