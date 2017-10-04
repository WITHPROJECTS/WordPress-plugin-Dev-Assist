(($)=>{
  let $root = $('.wpda-reset');
  if(!$root.length) return;
  // ===========================================================================
  // タブ操作
  {
    $tabs      = $('.wpda-tab li a', $root);
    $cnts      = $('.wpda-box', $root);
    $activeTab = false;
    $activeCnt = false;
    $cnts.addClass('deactive');

    $tabs.on('click', (e)=>{
      let $this = $(e.currentTarget);
      e.preventDefault();
      if( $activeTab ){
        $activeTab.removeClass('active');
        $cnts.addClass('deactive');
      }
      $activeTab = $this.parent();
      $activeTab.addClass('active');
      $(`.wpda-box${$this.attr('href').replace('#', '.')}`, $root).removeClass('deactive');
    });
    $($tabs[0]).trigger('click');
  }
})(jQuery);
