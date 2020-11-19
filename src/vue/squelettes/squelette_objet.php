<!DOCTYPE html>
<html lang="fr">
  <head>
    <title> <?php echo $this->titre; ?> </title>
  </head>
  <body>
  <h1> <?php echo $this->titre; ?> </h1>
  <?php if ($this->feedback !== '') { ?>
      <div><?php echo $this->feedback; ?></div>
  <?php } ?>
  <ul> <?php echo $this->contenu;?> </ul>
  </body>
</html>
