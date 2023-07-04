<?php $render('partial/header', $data); ?>
    <h1 class="mb-2">Register</h1>

    <?php $beginForm('UserController', 'Register', method: 'post'); ?>
        <div class="form-group">
            <label for="userName"
                   class="form-label
            ">Username</label>
            <input class="form-control"
                   id="userName"
                   name="userName"
                   required="true"
                   placeholder="Please enter a username"

                   value="<?php $htmlOut($data['userName']); ?>"
            >
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input class="form-control"
                   id="password"
                   type="password"
                   name="password"
                   value="<?php $htmlOut($data['password']); ?>"
                   required="true"
                   placeholder="Please enter a passwort with a minimum length of 4 characters"
            />
        </div>
        <button class="btn btn-primary">Register</button>
    <?php $endForm(); ?>
    <?php $render('partial/footer', $data);?>