<?php

declare(strict_types=1);

/**
 * Class that generates cryptographically secure random passwords
 *
 * @author Renan Luiz Vendramini <renan@alboompro.com>
 * @version v3.1
 */
class RandomPassword
{
    private const STR_LENGTH = 20;

    /**
     * Gets character groups for password generation
     *
     * @param array<string> $pickedGroups Array of group names to use
     * @return array<string, array<int, string>> Available character groups
     */
    private static function characters(array $pickedGroups = []): array
    {
        $groups = [
            'lowercase' => range('a', 'z'),
            'uppercase' => range('A', 'Z'),
            'numbers' => range('0', '9'),
            'special' => ['!', '@', '#', '$', '%', '&', '*', '(', ')', '-', '_', '=', '+', '.', ':', ';', '\\', '/', ']', '[', '}', '{', '~', '^', ',', '|'],
        ];

        return empty($pickedGroups) ? $groups : array_filter(
            $groups,
            fn ($key) => in_array($key, $pickedGroups, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Cryptographically secure Fisher-Yates shuffle
     *
     * @param array<int, string> $array Array to shuffle in place
     */
    private static function secureShuffle(array &$array): void
    {
        $count = count($array);

        for ($i = $count - 1; $i > 0; $i--) {
            $j = random_int(0, $i);

            if ($i !== $j) {
                [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
            }
        }
    }

    /**
     * Generates a random password based on configurations
     *
     * When multiple character groups are selected, the generated password is
     * guaranteed to contain at least one character from each selected group.
     *
     * @param int|null $givenLength Desired password length (default: 20)
     * @param array<string> $groups Character groups to use: 'lowercase', 'uppercase', 'numbers', 'special'
     * @return string Generated password
     */
    public static function generate(?int $givenLength = null, array $groups = []): string
    {
        $characters = self::characters($groups);
        $length = $givenLength ?? self::STR_LENGTH;

        if (empty($characters) || $length <= 0) {
            return '';
        }

        $keys = array_keys($characters);
        $keyCount = count($keys);
        $password = [];

        // Guarantee at least one character from each selected group
        // Skip if the requested length is too short to accommodate all groups
        if ($length >= $keyCount) {
            foreach ($keys as $key) {
                $group = $characters[$key];

                if ($group === []) {
                    continue;
                }

                $password[] = $group[random_int(0, count($group) - 1)];
            }
        }

        // Fill remaining positions with random picks
        $remaining = $length - count($password);

        for ($i = 0; $i < $remaining; $i++) {
            $key = $keys[random_int(0, $keyCount - 1)];
            $group = $characters[$key];

            if ($group === []) {
                continue;
            }

            $password[] = $group[random_int(0, count($group) - 1)];
        }

        self::secureShuffle($password);

        return implode('', $password);
    }
}
