<?php

declare(strict_types=1);

namespace JCIT\twig\validators;

use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;
use yii\validators\Validator;

class TwigValidator extends Validator
{
    /** @var array<string, string> */
    public array $context;
    /** @var array<int|string, string> */
    public array $mandatoryVariables = [];
    public \Closure $outputTest;
    public SecurityPolicy $policy;
    public bool $strict = true;
    /** @var array<string, mixed> */
    public array $variables = [];

    /**
     * @param array<string, mixed> $variables
     * @return array<string, string>
     */
    private function createContext(array $variables): array
    {
        $result = [];
        foreach ($variables as $index => $name) {
            if (is_string($name)) {
                $result[$name] = md5((string) mt_rand());
            } elseif (is_array($name)) {
                $subContext = $this->createContext($name);
                if (substr($index, -2, 2) === '[]') {
                    $result[substr($index, 0, -2)][] = $subContext;
                } else {
                    $result[$index] = $subContext;
                }
            }
        }
        return $result;
    }

    /**
     * @return array<int, mixed>|null
     */
    protected function validateValue($value): array|null
    {
        $errors = [];

        if (!is_string($value)) {
            return [
                \Yii::t(
                    'JCIT',
                    '{attribute} must be a string.'
                ),
                []
            ];
        }

        $value = html_entity_decode($value, ENT_QUOTES, 'utf-8');
        $loader = new ArrayLoader([
            '' => $value
        ]);

        $twig = new Environment(
            $loader,
            [
                'debug' => true,
                'strict_variables' => $this->strict
            ]
        );
        $twig->addExtension(new SandboxExtension($this->policy, true));

        try {
            $context = $this->context ?? $this->createContext($this->variables);
            $result = $twig->render('', $context);

            if (isset($this->outputTest)) {
                ($this->outputTest)($result);
            }

            foreach ($this->mandatoryVariables as $variable => $message) {
                if (is_int($variable)) {
                    $variable = $message;
                    $message = \Yii::t(
                        'JCIT',
                        'Could not find value of mandatory variable "{variable}" in rendered template.'
                    );
                }

                if (strpos($result, $context[$variable]) === false) {
                    $errors[] = strtr(
                        $message,
                        [
                            '{variable}' => $variable
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) == 0) {
            return null;
        }

        return [
            implode(', ', $errors),
            []
        ];
    }
}
