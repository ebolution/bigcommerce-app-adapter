<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   Proprietary
 */

namespace Ebolution\BigcommerceAppAdapter\Domain\ValueObjects;

use Illuminate\Support\Facades\Request;

final class BCAuthorizedUserSaveRequest extends Request
{
    private ?array $value;
    private string $date;

    /**
     * @param array|null $value
     * @param string $date
     */
    public function __construct(?array $value, string $date)
    {
        $this->value = $value;
        $this->date = $date;
    }

    /**
     * @return array|null
     */
    public function value(): ?array
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function date(): string
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function handler(): array
    {
        return array_merge($this->value, [
            'created_at' => $this->date(),
            'updated_at' => $this->date()
        ]);
    }
}
