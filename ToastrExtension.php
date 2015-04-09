<?php

namespace ITE\Js\Notification;

use ITE\Common\CdnJs\Resource\Reference;
use ITE\Js\Notification\Definition\Builder\PluginDefinition;
use ITE\Js\Notification\Plugin\NotificationPlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;


/**
 * Class ToastrExtension
 *
 * @package ITE\Js\Notification
 * @author  sam0delkin <t.samodelkin@gmail.com>
 */
class ToastrExtension extends NotificationPlugin
{
    /**
     * @var bool
     */
    private static $loaded = false;
    /**
     * @var string
     */
    private $version;

    /**
     * @param $version
     */
    function __construct($version)
    {
        $this->version = $version;
    }


    public function getCdnJavascripts($debug)
    {
        if ($debug) {
           return [
               new Reference('toastr.js', $this->version, 'js/toastr.js')
           ];
        } else {
            return [
                new Reference('toastr.js', $this->version, 'js/toastr.min.js')
            ];
        }
    }

    public function getCdnStylesheets($debug)
    {
        if ($debug) {
            return [
                new Reference('toastr.js', $this->version, 'css/toastr.css')
            ];
        } else {
            return [
                new Reference('toastr.js', $this->version, 'css/toastr.min.css')
            ];
        }
    }

    public function getJavascripts()
    {
        return [__DIR__.'/Resources/public/js/sf.notification.toastr.js'];
    }

    public function addConfiguration(ArrayNodeDefinition $pluginsNode, ContainerBuilder $container)
    {
        if (!($pluginsNode instanceof PluginDefinition)) {
            return false;
        }

        $builder = new TreeBuilder();
        $node = $builder->root('toastr');

        $node
            ->canBeEnabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('version')
                    ->defaultValue('2.1.0')
                ->end()
            ->end()
            ;

        return $node;
    }


    public function loadConfiguration(array $config, ContainerBuilder $container)
    {
        if ($config['extensions']['notifications']['toastr']['enabled']) {
            $container->setParameter('ite_js.notifications.toastr.version', $config['extensions']['notifications']['toastr']['version']);
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
            $loader->load('services.yml');
        }
    }

}