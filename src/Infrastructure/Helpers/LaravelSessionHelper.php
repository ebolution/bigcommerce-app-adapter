<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure\Helpers;

use \Ebolution\BigcommerceAppAdapter\Application\Contracts\PersistenceInterface;

class LaravelSessionHelper implements PersistenceInterface
{
    private string $prefix = "bigcommerce-app-adapter::";

    public function retrieve(): array
    {
        $len = strlen($this->prefix);
        $module_items = [];
        $all_items = session()->all();

        foreach($all_items as $key => $value) {
            if (str_starts_with($key, $this->prefix)) {
                $module_items[substr($key, $len)] = $value;
            }
        }

        return $module_items;
    }

    public function persist(array $data): void
    {
        $session = session();
        foreach ($data as $key => $value) {
            $session->put("{$this->prefix}{$key}", $value);
        }

        $session->regenerate();
        $session->save();
    }
}
