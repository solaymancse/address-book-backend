# REFACTOR_NOTES.md

## Code Review: Original Implementation Analysis

Upon reviewing the provided PHP snippet, several architectural flaws, bugs, and best practice violations were identified:

1. **Broken Constructor & Argument Mismatch:**
   * *Problem:* The code instantiates `$car = new TeslaCar("Model_3");` passing `"Model_3"` as an argument. However, neither `TeslaCar` nor its parent class `Car` defines a constructor that accepts arguments (`__construct()` is empty). 
   * *Result:* `$this->name` remains uninitialized (`null`), making `$car->get_name()` output nothing.

2. **Hardcoded Static Text Violating Extensibility:**
   * *Problem:* The base `Car` class hardcodes `"The Tesla Car finishes assembly..."` inside `print_assembly()`. A generic base class should not assume specific brand details if it is intended to represent multiple car types.

3. **Mixing Output Contexts (HTML in Business Logic):**
   * *Problem:* Printing raw HTML tags (`<br>`) directly inside execution logic restricts code portability, breaking execution if run via CLI or API environments.

4. **Lack of Type Safety and Visibility Modifiers:**
   * *Problem:* Properties and methods rely on legacy visibility and lack modern PHP type hints (`string`, `void`), which reduces code maintainability and clarity.

---

## Refactored Code Solution

```php
<?php

class Car {
    protected string $name;

    public function __construct(string $name) {
        $this->name =$name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function printAssembly(): void {
        echo "The " . htmlspecialchars($this->name) . " finishes assembly every Friday at 5pm.\n";
    }
}

class TeslaCar extends Car {
    public function __construct(string $name = "Tesla Model 3") {
        parent::__construct($name);
    }

    public function generateAssemblyReports(): void {
        echo "Generating assembly reports...\n";
        echo "Exporting CSV format reports...\n";
        echo "Printing reports...\n";
    }
}

// Execution
$car = new TeslaCar("Model_3");
echo $car->getName();
echo PHP_EOL;
$car->generateAssemblyReports();