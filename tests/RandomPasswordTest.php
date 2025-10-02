<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Test suite for RandomPassword class
 */
final class RandomPasswordTest extends TestCase
{
    /**
     * Tests basic password generation with default parameters
     */
    public function testGenerateDefaultPassword(): void
    {
        $password = RandomPassword::generate();

        $this->assertEquals(20, strlen($password), 'Default password length should be 20');
    }

    /**
     * Tests password generation with custom length
     */
    public function testGenerateCustomLength(): void
    {
        $lengths = [10, 15, 25, 50, 100];

        foreach ($lengths as $length) {
            $password = RandomPassword::generate($length);
            $this->assertEquals($length, strlen($password), "Password should have length $length");
        }
    }

    /**
     * Tests password generation with minimum length
     */
    public function testGenerateMinimumLength(): void
    {
        $password = RandomPassword::generate(1);

        $this->assertEquals(1, strlen($password));
    }

    /**
     * Tests password generation with zero length
     */
    public function testGenerateZeroLength(): void
    {
        $password = RandomPassword::generate(0);

        $this->assertEquals(0, strlen($password));
        $this->assertEmpty($password);
    }

    /**
     * Tests that generated passwords are unique
     */
    public function testGenerateUniquePasswords(): void
    {
        $passwords = [];
        $count = 100;

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = RandomPassword::generate(20);
        }

        $uniquePasswords = array_unique($passwords);
        $this->assertCount($count, $uniquePasswords, 'All generated passwords should be unique');
    }

    /**
     * Tests password generation with only lowercase characters
     */
    public function testGenerateLowercaseOnly(): void
    {
        $password = RandomPassword::generate(50, ['lowercase']);

        $this->assertMatchesRegularExpression('/^[a-z]+$/', $password, 'Password should contain only lowercase letters');
    }

    /**
     * Tests password generation with only uppercase characters
     */
    public function testGenerateUppercaseOnly(): void
    {
        $password = RandomPassword::generate(50, ['uppercase']);

        $this->assertMatchesRegularExpression('/^[A-Z]+$/', $password, 'Password should contain only uppercase letters');
    }

    /**
     * Tests password generation with only numbers
     */
    public function testGenerateNumbersOnly(): void
    {
        $password = RandomPassword::generate(50, ['numbers']);

        $this->assertMatchesRegularExpression('/^[0-9]+$/', $password, 'Password should contain only numbers');
    }

    /**
     * Tests password generation with only special characters
     */
    public function testGenerateSpecialOnly(): void
    {
        $password = RandomPassword::generate(50, ['special']);
        $specialChars = preg_quote('!@#$%&*()-_=+.:;\\\/][}{~^,|', '/');

        $this->assertMatchesRegularExpression("/^[$specialChars]+$/", $password, 'Password should contain only special characters');
    }

    /**
     * Tests password generation with lowercase and uppercase
     */
    public function testGenerateLowercaseAndUppercase(): void
    {
        $password = RandomPassword::generate(100, ['lowercase', 'uppercase']);

        $this->assertMatchesRegularExpression('/^[a-zA-Z]+$/', $password, 'Password should contain only letters');
        $this->assertMatchesRegularExpression('/[a-z]/', $password, 'Password should contain at least one lowercase letter');
        $this->assertMatchesRegularExpression('/[A-Z]/', $password, 'Password should contain at least one uppercase letter');
    }

    /**
     * Tests password generation with multiple character groups
     */
    public function testGenerateMultipleGroups(): void
    {
        $password = RandomPassword::generate(100, ['lowercase', 'uppercase', 'numbers']);

        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $password, 'Password should contain only alphanumeric characters');
        $this->assertMatchesRegularExpression('/[a-z]/', $password, 'Password should contain at least one lowercase letter');
        $this->assertMatchesRegularExpression('/[A-Z]/', $password, 'Password should contain at least one uppercase letter');
        $this->assertMatchesRegularExpression('/[0-9]/', $password, 'Password should contain at least one number');
    }

    /**
     * Tests password generation with all character groups
     */
    public function testGenerateAllGroups(): void
    {
        $password = RandomPassword::generate(200);

        $this->assertMatchesRegularExpression('/[a-z]/', $password, 'Password should contain lowercase letters');
        $this->assertMatchesRegularExpression('/[A-Z]/', $password, 'Password should contain uppercase letters');
        $this->assertMatchesRegularExpression('/[0-9]/', $password, 'Password should contain numbers');
    }

    /**
     * Tests password with invalid group names (should use all groups)
     */
    public function testGenerateInvalidGroups(): void
    {
        $password = RandomPassword::generate(50, ['invalid', 'nonexistent']);

        // When no valid groups are found, it should return empty string
        $this->assertEmpty($password);
    }

    /**
     * Tests that password generation doesn't produce identical consecutive passwords
     */
    public function testConsecutivePasswordsAreDifferent(): void
    {
        $password1 = RandomPassword::generate(30);
        $password2 = RandomPassword::generate(30);

        $this->assertNotEquals($password1, $password2, 'Consecutive passwords should be different');
    }

    /**
     * Tests password randomness distribution for lowercase
     */
    public function testLowercaseRandomnessDistribution(): void
    {
        $password = RandomPassword::generate(1000, ['lowercase']);
        $charCounts = count_chars($password, 1);

        // Should have at least 10 different characters in 1000 chars
        $this->assertGreaterThanOrEqual(10, count($charCounts), 'Password should have good character distribution');
    }

    /**
     * Tests that null length parameter uses default
     */
    public function testNullLengthUsesDefault(): void
    {
        $password = RandomPassword::generate(null);

        $this->assertEquals(20, strlen($password));
    }

    /**
     * Tests large password generation
     */
    public function testGenerateLargePassword(): void
    {
        $password = RandomPassword::generate(10000);

        $this->assertEquals(10000, strlen($password));
    }

    /**
     * Tests password generation performance
     */
    public function testGenerationPerformance(): void
    {
        $start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            RandomPassword::generate(20);
        }

        $end = microtime(true);
        $duration = $end - $start;

        // Should generate 1000 passwords in less than 1 second
        $this->assertLessThan(1.0, $duration, 'Should generate 1000 passwords quickly');
    }

    /**
     * Tests that empty groups array uses all character types
     */
    public function testEmptyGroupsUsesAllTypes(): void
    {
        $password = RandomPassword::generate(200, []);

        // With empty array, all groups should be used
        $this->assertMatchesRegularExpression('/[a-z]/', $password, 'Should contain lowercase');
        $this->assertMatchesRegularExpression('/[A-Z]/', $password, 'Should contain uppercase');
        $this->assertMatchesRegularExpression('/[0-9]/', $password, 'Should contain numbers');
    }

    /**
     * Tests mixed valid and invalid group names
     */
    public function testMixedValidInvalidGroups(): void
    {
        $password = RandomPassword::generate(100, ['lowercase', 'invalid', 'uppercase']);

        $this->assertMatchesRegularExpression('/^[a-zA-Z]+$/', $password, 'Should use only valid groups');
    }

    public function testGeneratePasswordWithInvalidLength(): void
    {
        $password = RandomPassword::generate(-1);

        $this->assertEmpty($password);
    }
}
