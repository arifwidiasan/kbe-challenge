<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
</head>
<body>

<div>
	<h1>Welcome to CodeIgniter!</h1>

	<div>
		<table>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Path File</th>
				<th>Actions</th>
			</tr>
			<?php foreach ($ebooks as $ebook): ?>
			<tr>
				<td><?php echo $ebook->id; ?></td>
				<td><?php echo $ebook->ebook_name; ?></td>
				<td><?php echo $ebook->pathfile; ?></td>
				<td>
					<a href="<?php echo base_url('ebooks/view/'.$ebook->id); ?>">View</a>
					<a href="<?php echo base_url('ebooks_controller/delete_ebook/'.$ebook->id); ?>">Delete</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>

</body>
</html>
