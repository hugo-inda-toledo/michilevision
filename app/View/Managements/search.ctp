<?php // app/views/posts/search.ctp ?>
<h1>Blog posts</h1>
<?php 
    echo $this->form->create("Management",array('action' => 'search'));
    echo $this->form->input("q", array('label' => 'Search for'));
    echo $this->form->end("Management");
?>
<p><?php echo $this->html->link("Add Management", "/managements/add"); ?>

<?php
	
	echo "<pre>";
	print_r($this->request->data);
	echo "</pre>";
	
	echo "<pre>";
	print_r($results);
	echo "</pre>";
?>