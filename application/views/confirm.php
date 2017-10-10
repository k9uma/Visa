<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div class="col-lg-offset-2 col-lg-4">
            <form role="form" method="POST" action="<?= $url; ?>">
                <?php foreach ($params as $key => $value): ?>
                    <input type="hidden" class="form-control" name="<?= $key; ?>" value="<?= $value; ?>"/>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary btn-raised btn-lg btn-block">Confirm</button>

            </form>
        </div>
    </body>
</html>
