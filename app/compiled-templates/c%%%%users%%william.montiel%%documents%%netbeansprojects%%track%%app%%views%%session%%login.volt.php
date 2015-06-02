<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=1">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
        <!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <?php echo $this->tag->getTitle(); ?>
        
        <?php echo $this->tag->javascriptInclude('library/jquery/jquery-1.11.3.min.js'); ?>
        <?php echo $this->tag->stylesheetLink('css/styles.css'); ?>
        
        <?php echo $this->tag->stylesheetLink('library/bootstrap-3.3.4/css/bootstrap.css'); ?>
        <?php echo $this->tag->javascriptInclude('library/bootstrap-3.3.4/js/bootstrap.min.js'); ?>

        <script type="text/javascript">
            var myBaseURL = '<?php echo $this->url->get(''); ?>';
        </script>
        
    <?php echo $this->tag->stylesheetLink('css/session-styles.css'); ?>

    </head>
    <body>
        <div class="container">
            
    <?php echo $this->flashSession->output(); ?>
    <form action="<?php echo $this->url->get('session/login'); ?>" method="POST" class="form-signin">
        <h2 class="form-signin-heading">Iniciar sesión</h2>
        <label for="username" class="sr-only">Nombre de usuario</label>
        <input type="text" id="username" class="form-control" placeholder="Nombre de usuario" required autofocus>
        <label for="password" class="sr-only">Contraseña</label>
        <input type="password" id="password" class="form-control" placeholder="Contraseña" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Continuar</button>
    </form>

        </div>
    </body>
</html>
