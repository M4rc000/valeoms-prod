<?php if (!empty($materials)): ?>
  <ul>
    <?php foreach ($materials as $material): ?>
      <li><?php echo $material->name; // Replace 'name' with the actual column name ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No active materials found.</p>
<?php endif; ?>
