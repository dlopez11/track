
<div id="pagination" class="text-center">
    <ul class="pagination">
        <li class="<?php echo (($page->current == 1) ? 'disabled' : 'enabled'); ?>">
            <a href="<?php echo $this->url->get($pagination_url); ?>?page=1"><i class="glyphicon glyphicon-fast-backward"></i></a>
        </li>
        <li class="<?php echo (($page->current == 1) ? 'disabled' : 'enabled'); ?>">
            <a href="<?php echo $this->url->get($pagination_url); ?>?page=<?php echo $page->before; ?>"><i class="glyphicon glyphicon-step-backward"></i></a>
        </li>
        <li>
            <span><b><?php echo $page->total_items; ?></b> registros </span><span>Página <b><?php echo $page->current; ?></b> de <b><?php echo $page->total_pages; ?></b></span>
        </li>
        <li class="<?php echo (($page->current >= $page->total_pages) ? 'disabled' : 'enabled'); ?>">
            <a href="<?php echo $this->url->get($pagination_url); ?>?page=<?php echo $page->next; ?>"><i class="glyphicon glyphicon-step-forward"></i></a>
        </li>
        <li class="<?php echo (($page->current >= $page->total_pages) ? 'disabled' : 'enabled'); ?>">
            <a href="<?php echo $this->url->get($pagination_url); ?>?page=<?php echo $page->last; ?>"><i class="glyphicon glyphicon-fast-forward"></i></a>
        </li>
    </ul>
</div>
