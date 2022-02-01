<?php

declare(strict_types=1);

namespace common\widgets;

use JCIT\twig\interfaces\TwigHelperInterface;
use yii\helpers\Html;

class TwigHelper extends \yii\base\Widget
{
    public bool $hasBootstrap4 = true;
    public TwigHelperInterface $helper;

    protected function renderMethods(): string
    {
        $result = '';
        $result .= Html::beginTag('dl', ['class' => ['row']]);
        foreach ($this->helper::methodDescriptions() as $method => $description) {
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
            ($this->hasBootstrap4) ? Html::a(\Yii::t('app', 'Bootstrap 4'), 'https://getbootstrap.com/docs/4.6/', ['target' => '_blank']) : null,
            Html::a(\Yii::t('app', 'Twig 3'), 'https://twig.symfony.com/doc/3.x/templates.html', ['target' => '_blank']),
        ]), ['encode' => false]);
        $result .= Html::tag('p', \Yii::t('app', 'While this approach is very powerful, it also requires some knowledge. If you can\'t get something to work, please contact us.'));
        $result .= Html::tag('p', \Yii::t('app', 'To provide flexibility and integration with there is a helper available in Twig "code". Please read the Twig documentation how to use the object, it\'s named <code>helper</code> and provides the following methods:'));

        $result .= $this->renderMethods();

        $result .= Html::endTag('div');
        return $result;
    }
}
