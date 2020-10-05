<div class="container">
    <?php if (isset($this->user)) : ?>
        <div class="jumbotron">
            <h1>Hello, <?= $this->escapeHTML($this->user->first_name . " " . $this->user->last_name); ?>!</h1>
            <p>...</p>
            <p>
                <a class="btn btn-default btn-lg" href="<?= $this->makeURL("profile"); ?>" role="button">Profile</a>
                <a class="btn btn-primary btn-lg" href="<?= $this->makeURL("login/logout"); ?>" role="button">Logout</a>
            </p>
        </div>
    <?php endif; ?>
</div>