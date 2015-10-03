<?php

namespace ITE\Js\Notification\Channel;

use ITE\Common\CdnJs\Resource\Reference;
use ITE\Js\Notification\Channel\AbstractChannel;
use ITE\Js\Notification\Definition\Builder\PluginDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class ToastrChannel
 *
 * @author  sam0delkin <t.samodelkin@gmail.com>
 */
class ToastrChannel extends AbstractChannel
{
    /**
     * @var string
     */
    private $version;

    /**
     * @param $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(ContainerBuilder $container)
    {
        $builder = new TreeBuilder();

        $node = $builder->root('toastr');
        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('version')
                    ->defaultValue('2.1.0')
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function loadConfiguration(array $config, ContainerBuilder $container)
    {
        if ($config['extensions']['notification']['channels']['toastr']['enabled']) {
            $container->setParameter(
                'ite_js.notification.channel.toastr.version',
                $config['extensions']['notification']['channels']['toastr']['version']
            );
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('sf.notification.toastr.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnName()
    {
        return 'toastr.js';
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnStylesheets($debug)
    {
        return [
            new Reference($this->getCdnName(), $this->version, $debug ? 'css/toastr.css' : 'css/toastr.min.css')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnJavascripts($debug)
    {
        return [
            new Reference($this->getCdnName(), $this->version, $debug ? 'js/toastr.js' : 'js/toastr.min.js')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts()
    {
        return [__DIR__.'/../Resources/public/js/sf.notification.toastr.js'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toastr';
    }
}
