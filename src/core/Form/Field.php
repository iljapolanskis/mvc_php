<?php

namespace MVC\Core\Form;

use MVC\Core\Models\Abstract\Model;

class Field
{
    public const TYPE_TEXT = "text";
    public const TYPE_NUMBER = "number";
    public const TYPE_EMAIL = "email";
    public const TYPE_TEXT_AREA = "textarea";
    public const TYPE_PASSWORD = "password";
    public const TYPE_CHECKBOX = "checkbox";
    public const TYPE_SUBMIT = "submit";

    private string $type;
    private string $label;

    public function __construct(public Model $model, public string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->label = $attribute;
    }

    /**
     * @param string $type
     * @return Field
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $label
     * @return Field
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function __toString()
    {
        $isInvalid = $this->model->hasError($this->attribute) ? 'is-invalid' : '';
        $error = !empty($isInvalid) ? "<div class='invalid-feedback'>{$this->model->getFirstError($this->attribute)}</div>" : '';

        if ($this->type === self::TYPE_CHECKBOX) {
            return <<<HTML
              <div class="mb-3 form-check">
                <input type="checkbox" value="{$this->model->{$this->attribute}}" name="{$this->attribute}" class="form-check-input {$isInvalid}" id="{$this->attribute}">
                <label class="form-check-label" for="{$this->attribute}">{$this->label}</label>
                {$error}
              </div>
            HTML;
        }

        return <<<HTML
            <div class="mt-12 mb-3">
                <label class="form-label" for="{$this->attribute}">{$this->label}</label>
                <input type="{$this->type}" name="$this->attribute" value="{$this->model->{$this->attribute}}" class="form-control {$isInvalid}" id="$this->attribute">
                {$error}
            </div>
        HTML;
    }
}