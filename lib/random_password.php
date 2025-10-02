<?php

declare(strict_types=1);

/**
 * Class that generates random passwords
 *
 * @author Renan Luiz Vendramini <renan@alboompro.com>
 * @version v3.0
 */
class RandomPassword
{
    private const STR_LENGTH = 20;

    /**
     * Gets character groups for password generation
     *
     * @param array<string> $pickedGroups Array of group names to use
     * @return array<string, array<int|string>> Available character groups
     */
    private static function characters(array $pickedGroups = []): array
    {
        $groups = [
            'lowercase' => range('a', 'z'),
            'uppercase' => range('A', 'Z'),
            'numbers' => range(0, 9),
            'special' => ['!', '@', '#', '$', '%', '&', '*', '(', ')', '-', '_', '=', '+', '.', ':', ';', '\\', '/', ']', '[', '}', '{', '~', '^', ',', '|'],
        ];

        return empty($pickedGroups) ? $groups : array_filter(
            $groups,
            fn ($key) => in_array($key, $pickedGroups, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Shuffles all character groups for better randomness
     *
     * @param array<string, array<int|string>> $characters Character groups
     * @return void
     */
    private static function shuffleCharacterGroups(array &$characters): void
    {
        foreach ($characters as $key => $value) {
            shuffle($characters[$key]);
        }
    }

    /**
     * Picks a random character group
     *
     * @param array<string, array<int|string>> $characters Available character groups
     * @return string Selected group name
     */
    private static function pickCharacterGroup(array $characters): string
    {
        $keys = array_keys($characters);
        $count = count($keys);

        if ($count === 0) {
            return '';
        }

        $randomIndex = random_int(0, $count - 1);

        return (string) $keys[$randomIndex];
    }

    /**
     * Picks a random character from available groups
     *
     * @param array<string, array<int|string>> $characters Available character groups
     * @return string|int Random character
     */
    private static function pickChar(array $characters): string|int
    {
        $group = self::pickCharacterGroup($characters);
        $groupLimit = count($characters[$group]);

        if ($groupLimit === 0) {
            return '';
        }

        $randomIndex = random_int(0, $groupLimit - 1);

        return $characters[$group][$randomIndex];
    }

    /**
     * Generates a random password based on configurations
     *
     * If you define custom character groups, they will automatically be considered in the character mix.
     *
     * @param int|null $givenLength Desired password length (default: 20)
     * @param array<string> $groups Character groups to use: 'lowercase', 'uppercase', 'numbers', 'special'
     * @return string Generated password
     */
    public static function generate(?int $givenLength = null, array $groups = []): string
    {
        $characters = self::characters($groups);
        $length = $givenLength ?? self::STR_LENGTH;

        // Return empty string if no valid character groups are available
        if (empty($characters) || $length <= 0) {
            return '';
        }

        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= self::pickChar($characters);
            self::shuffleCharacterGroups($characters);
        }

        return $password;
    }
}
