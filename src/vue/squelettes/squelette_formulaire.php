<!DOCTYPE html>
<html lang="fr">
  <head>
    <title> <?php echo $this->titre; ?> </title>
  </head>
  <body>
    <h1> <?php echo $this->titre; ?> </h1>
    <ul> <?php echo $this->menu ?> </ul>
    <?php if ($this->feedback !== '') { ?>
             <div><?php echo $this->feedback; ?></div>
    <?php } ?>
    <?php echo $this->contenu; ?>
  </body>
</html>
