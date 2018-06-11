<?php ! defined('__TYPECHO_ROOT_DIR__') and exit();?>
<?php  if ($typechoVersion <= 0.8): ?>
    <script src="<?php echo $options->pluginUrl('AppStore/static/js/jquery.js'); ?>"></script>
    <script>
        var $jQuery = jQuery.noConflict(true);
    </script>
<?php else: ?>
    <script>
        var $jQuery = $;
    </script>
<?php endif; ?>
<script>
    (function($) {

        "use strict"

        function init()
        {
            $('.as-card .as-version-selector').change(function() {

                var $version = $(this).children(':selected');
                var $card = $(this).parent().parent();

                $card.children('.as-require').html($version.data('require'));
                $card.children('.as-description').html($version.data('description'));
                $card.children('.as-author').html($version.data('author'));

            });

            $('.as-card .as-install').click(function() {
                var $this = $(this);
                var $card = $this.parent().parent();
                var $version = $card.children('.as-versions').children('.as-version-selector').children(':selected');
                if ($card.data('existed')) {
                    if ($version.data('activated')) {
                        if (! confirm('<?php echo _t('该插件该版本已经激活使用了！\n确定继续安装吗？'); ?>')) {
                            return false;
                        }
                    } else {
                        if (! confirm('<?php echo _t('该插件该版本已经存在了！\n确定继续安装吗？'); ?>')) {
                            return false;
                        }
                    }
                }
                $.ajax({
                    url: '<?php echo str_replace('/market', '/install', Typecho_Request::getInstance()->makeUriByRequest()); ?>',
                    dataType: 'json',
                    data: {
                        version: $version.val(),
                        require: $version.data('require'),
                        plugin:  $card.data('name')
                    },
                    beforeSend: function() {
                        $this.attr('disabled', true).text('安装中');
                    }
                }).always(function() {
                    $this.attr('disabled', false);
                }).fail(function() {
                    alert('安装失败');
                    if ($card.data('existed')) {
                        $this.text('重装');
                    } else {
                        $this.text('安装');
                    }
                }).done(function(result) {
                    if (result.status) {
                        $card.data('existed', 1);
                        $version.data('activated', result.activated);
                        $this.next().children('i').addClass('as-existed active');
                        alert('安装成功');
                        window.location.reload();
                    } else {
                        alert(result.error);
                    }
                });

            });

        }

        init();
    })($jQuery);
</script>
