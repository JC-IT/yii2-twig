<?php

declare(strict_types=1);

namespace JCIT\twig\widgets;

use JCIT\twig\interfaces\TwigHelperInterface;
use yii\helpers\Html;

class TwigHelper extends \yii\base\Widget
{
    protected array $_methods;
    public string $bootstrap4Version = '4.6';
    public string $bootstrap5Version = '5.3';
    public bool $hasBootstrap4 = true;
    public bool $hasBootstrap5 = false;
    public TwigHelperInterface $helper;

    protected function getMethods(): array
    {
        if (!isset($this->_methods)) {
            $this->_methods = $this->helper::methodDescriptions();
        }

        return $this->_methods;
    }

    protected function renderMethods(): string
    {
        $result = '';
        $result .= Html::beginTag('dl', ['class' => ['row']]);
        foreach ($this->getMethods() as $method => $description) {
            $result .= Html::tag('dt', Html::tag('code', $method), ['class' => ['col-sm-3']]);
            $result .= Html::tag('dd', $description, ['class' => ['col-sm-9']]);
        }
        $result .= Html::endTag('dl');
        return $result;
    }

    public function run(): string
    {
        $result = parent::run();
        $result .= Html::beginTag('div', ['class' => ['callout', 'callout-info', 'mt-3']]);
        $result .= Html::tag('p', \Yii::t('app', 'In the editor above you can create content with advanced mechanics:'));
        $result .= Html::ul(array_filter([
            ($this->hasBootstrap4) ? Html::a(\Yii::t('app', 'Bootstrap 4'), "https://getbootstrap.com/docs/{$this->bootstrap4Version}/", ['target' => '_blank']) : null,
            ($this->hasBootstrap5) ? Html::a(\Yii::t('app', 'Bootstrap 5'), "https://getbootstrap.com/docs/{$this->bootstrap5Version}/", ['target' => '_blank']) : null,
            Html::a(\Yii::t('app', 'Twig 3'), 'https://twig.symfony.com/doc/3.x/templates.html', ['target' => '_blank']),
        ]), ['encode' => false]);
        $result .= Html::tag('p', \Yii::t('app', 'While this approach is very powerful, it also requires some knowledge. If you can\'t get something to work, please contact us.'));
        if (!empty($this->getMethods())) {
            $result .= Html::tag('p', \Yii::t('app', 'To provide flexibility and integration with there is a helper available in Twig "code". Please read the Twig documentation how to use the object, it\'s named <code>helper</code> and provides the following methods:'));

            $result .= $this->renderMethods();
        }

        $result .= Html::endTag('div');
        return $result;
    }
}
