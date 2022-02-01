<?php

declare(strict_types=1);

namespace JCIT\twig\interfaces;

use Twig\Sandbox\SecurityPolicy;

interface TwigHelperInterface
{
    /**
     * Descrition of the methods available in twig, i.e.:
     * [
     *      'urlToPage(string pageSlug)' => \Yii::t('app', 'Generate an url to a page, requires slug as a parameter.'),
     * ]
     * @return array<string, string>
     */
    public static function methodDescriptions(): array;

    /**
     * List all the methods that are safe for the @see SecurityPolicy
     * @return array <int, string>
     */
    public static function methodsForTwigSecurityPolicy(): array;
}
