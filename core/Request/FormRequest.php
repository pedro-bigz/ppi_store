<?php namespace Core\Request;

use Core\Exceptions\ApplicationException;
use Core\Exceptions\RequiredException;
use Core\Exceptions\InvalidTypeException;
use Core\Exceptions\InvalidValueException;
use Core\Exceptions\AuthorizationException;

class FormRequest extends Request
{
    protected const REQUIRED = 'required';
    protected const NULLABLE = 'nullable';

    protected const ANY_T = '*';
    protected const INT_T = 'int';
    protected const URL_T = 'url';
    protected const EMAIL_T = 'email';
    protected const FLOAT_T = 'float';
    protected const BOOL_T = 'boolean';
    protected const STRING_T = 'string';

    protected array|null $validated = null;

    public function validated()
    {
        if (is_null($this->validated)) {
            return $this->validateResolved();
        }
        return $this->validated;
    }

    public function getValidated(): array
    {
        return $this->validated ?: [];
    }

    public function getAuthorization()
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize();
        }
        return true;
    }

    public function getRules()
    {
        if (method_exists($this, 'rules')) {
            return $this->rules();
        }
        return [];
    }

    public function validateResolved()
    {
        if (! $this->getAuthorization()) {
            throw AuthorizationException::create();
        }
        return $this->validated = $this->validate();
    }

    public function validate()
    {
        $form = [];
        foreach ($this->getRules() as $field => $rule) {
            $input = $this->get($field);

            if (count($rule) == 1) {
                [$required, $type, $options] = [$rule[0], '*', []];
            }
            if (count($rule) == 2) {
                [$required, $type, $options] = [...$rule, []];
            }
            if (count($rule) == 3) {
                [$required, $type, $options] = $rule;
            }

            if ($required === 'required' && is_null($input)) {
                throw RequiredException::create($field);
            }
            if ($type !== '*' && !$this->validateType($input, $type)) {
                throw InvalidTypeException::create($field, $type, gettype($input));
            }
            if (is_null($sanitized = $this->resolveSanitize($input, $type, $options))) {
                throw InvalidValueException::create($field);
            }

            $form[$field] = $sanitized;
        }

        return $form;
    }

    public function resolveType($input, $filter)
    {
        return filter_var($input, $filter, FILTER_NULL_ON_FAILURE);
    }

    public function sanitizeInput($input, $filter, $options = 0)
    {
        return filter_var($input, $filter, $options);
    }

    public function validateType($input, $type)
    {
        $validators = [
            self::EMAIL_T => fn() => (
                $this->resolveType($input, FILTER_VALIDATE_EMAIL) != null
            ),
            self::INT_T =>  fn() => (
                $this->resolveType($input, FILTER_VALIDATE_INT) != null
            ),
            self::BOOL_T =>  fn() => (
                $this->resolveType($input, FILTER_VALIDATE_BOOLEAN) != null
            ),
            self::FLOAT_T =>  fn() => (
                $this->resolveType($input, FILTER_VALIDATE_FLOAT) != null
            ),
            self::URL_T =>  fn() => (
                $this->resolveType($input, FILTER_VALIDATE_URL) != null
            ),
            self::STRING_T =>  fn() => (
                gettype($input) === $type
            ),
        ];

        if (!$validator = $validators[$type]) {
            return true;
        }

        return is_null($input) || $validator();
    }

    public function resolveSanitize($input, string $type, array $options = [])
    {
        if (empty($options)) $options = 0;

        $validators = [
            self::BOOL_T =>  fn() => !empty($input),
            self::STRING_T => fn() => htmlspecialchars(trim($input)),
            self::EMAIL_T => fn() => (
                $this->sanitizeInput(trim($input), FILTER_SANITIZE_EMAIL, $options)
            ),
            self::URL_T => fn() => (
                $this->sanitizeInput(trim($input), FILTER_SANITIZE_URL, $options)
            ),
            self::INT_T => fn() => (
                $this->sanitizeInput($input, FILTER_SANITIZE_NUMBER_INT, $options)
            ),
            self::FLOAT_T => fn() => (
                $this->sanitizeInput($input, FILTER_SANITIZE_NUMBER_FLOAT, $options)
            ),
            self::ANY_T =>  fn() => (
                $this->sanitizeInput(trim($input), FILTER_UNSAFE_RAW, [
                    'options' => $options ?: [],
                    'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_ENCODE_LOW | FILTER_FLAG_ENCODE_HIGH,
                ])
            ),
        ];

        if (!$validator = $validators[$type]) {
            return null;
        }

        return $validator();
    }
}