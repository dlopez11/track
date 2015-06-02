<ul class="nav nav-pills pull-right">
    <?php foreach ($this->smartMenu->get() as $item) { ?>
        <li role="presentation" class="<?php echo $item->class; ?>">
            <a href="<?php echo $this->url->get($item->url); ?>"><?php echo $item->title; ?></a>
        </li>
    <?php } ?> 
</ul>