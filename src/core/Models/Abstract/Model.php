<?php

namespace MVC\Core\Models\Abstract;

use InvalidArgumentException;
use MVC\Core\Application;
use MVC\Core\Models\Traits\Validator;
use MVC\Core\Models\Traits\ValidationRules;

abstract class Model implements ValidationRules
{
    use Validator;

    public function __construct(protected array $errors = []) {}

    public function populate(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;


    /**
     * @throws InvalidArgumentException
     */
    public function validate(): array
    {
        foreach ($this->rules() as $attribute => $rules) {
            $attributeValue = $this->{$attribute};

            if (!is_array($rules)) {
                throw new InvalidArgumentException("Rule must be an array with format [rule => value]");
            }

            foreach ($rules as $rule => $value) {
                if ($rule === self::RULE_REQUIRED && $attributeValue === null) {
                    $this->appendError($attribute, "Field is required");
                }

                if ($rule === self::RULE_EMAIL && !filter_var($attributeValue, FILTER_VALIDATE_EMAIL)) {
                    $this->appendError($attribute, "Field must be a valid email");
                }

                if ($rule === self::RULE_MATCH) {
                    if (!property_exists($this, $value)) {
                        throw new InvalidArgumentException("Match rule must be defined with a valid {static::class} attribute");
                    }
                    if ($attributeValue !== $this->{$value}) {
                        $this->appendError($attribute, "Field $attribute must match with field $value");
                    }
                }

                if ($rule === self::RULE_MIN && strlen($attributeValue) < $value) {
                    $this->appendError($attribute, "Field must be at least $value characters long");
                }

                if ($rule === self::RULE_MAX && strlen($attributeValue) > $value) {
                    $this->appendError($attribute, "Field must be at most $value characters long");
                }

                if ($rule === self::RULE_UNIQUE && $this->exists($attribute, $attributeValue)) {
                    $this->appendError($attribute, "Field must be unique");
                }
            }
        }

        return $this->errors;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isValid(): bool
    {
        $this->validate();
        return count($this->errors) === 0;
    }

    protected function appendError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError(string $attribute): bool
    {
        // FIXME: Probably not the best way to do this
        return isset($this->errors[$attribute]);
    }

    public function getFirstError(string $attribute): string
    {
        return $this->getErrors()[$attribute][0];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function exists(string $attribute, string $value): bool
    {
        $stmt = Application::$app->db->prepare("SELECT * FROM {$this->table()} WHERE $attribute = :attr");
        $stmt->bindValue(":attr", $value);
        $stmt->execute();
        return (bool)$stmt->fetchObject();
    }
}