<?php

namespace ITE\Js\Notification\Channel;

use ITE\Common\CdnJs\CdnAssetReference;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class ToastrChannel
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ToastrChannel extends AbstractChannel
{
    /**
     * @var array
     */
    protected $cdn;

    /**
     * @param array $cdn
     */
    public function setCdn(array $cdn)
    {
        $this->cdn = $cdn;
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
        ;
        if ($this->hasCdn()) {
            $node
                ->children()
                    ->arrayNode('cdn')
                        ->canBeUnset()
                        ->canBeDisabled()
                        ->children()
                            ->scalarNode('version')
                                ->defaultValue($this->getDefaultCdnVersion())
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ;
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function loadConfiguration(array $config, ContainerBuilder $container)
    {
        if ($config['extensions']['notification']['channels']['toastr']['enabled']) {
            if ($this->hasCdn()) {
                $container->setParameter(sprintf('ite_js.notification.channel.%s.cdn', static::getName()), $config['extensions']['notification']['channels']['toastr']['cdn']);
            }
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('sf.notification.toastr.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCdnVersion()
    {
        return '2.1.0';
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
            new CdnAssetReference(
                $this->getCdnName(),
                $this->getCdnVersion(),
                $debug ? 'css/toastr.css' : 'css/toastr.min.css'
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnJavascripts($debug)
    {
        return [
            new CdnAssetReference(
                $this->getCdnName(),
                $this->getCdnVersion(),
                $debug ? 'js/toastr.js' : 'js/toastr.min.js'
            ),
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
