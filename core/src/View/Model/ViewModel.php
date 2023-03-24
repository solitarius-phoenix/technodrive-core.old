<?php

namespace Technodrive\Core\View\Model;

/**
 *
 */
class ViewModel
{
    /**
     * View variables
     * @var array
     */
    protected array $variables = [];

    protected string $template = '';

    /**
     * @param array|null $variables
     */
    public function __construct(?array $variables = null)
    {
        if (null === $variables) {
            $variables = new \ArrayObject();
        }

        $this->setVariables($variables);
    }

    /**
     * set variable value
     * @var string key
     * @var mixed value
     */
    public function __set(string $key, mixed $value): void
    {
        $this->setVariable($key, $value);
    }

    public function __get(string $name): string
    {
        if (!$this->hasVariable($name)) {
            return '';
        }

        $variables = $this->getVariables();
        return $variables[$name];
    }

    public function __isset(string $name)
    {
        return $this->hasVariable($name);
    }


    /**
     * @param \Traversable $variables
     * @return $this
     */
    protected function setVariables(iterable $variables): self
    {
        foreach ($variables as $key => $value) {
            $this->setVariable((string)$key, $value);
        }
        return $this;
    }

    protected function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set view variable
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasVariable(string $key): bool
    {
        return isset($this->variables[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getVariable(string $key): mixed
    {
        return $this->variables[$key];
    }

    public function setTemplate(string $templateName): void
    {
        $this->template = $templateName;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

}