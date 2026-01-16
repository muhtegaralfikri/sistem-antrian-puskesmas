<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'key',
        'value',
        'description',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $updatedField = 'updated_at';

    // Cache settings
    private array $cache = [];

    /**
     * Get setting value by key
     */
    public function get(string $key, $default = null)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $setting = $this->where('key', $key)->first();

        if ($setting) {
            $value = $this->cache[$key] = $setting['value'];
            return $value;
        }

        return $default;
    }

    /**
     * Set setting value
     */
    public function setSetting(string $key, $value): bool
    {
        $existing = $this->where('key', $key)->first();

        $data = [
            'key' => $key,
            'value' => $value,
        ];

        if ($existing) {
            $result = $this->update($existing['id'], $data);
        } else {
            $result = $this->insert($data);
        }

        // Update cache
        $this->cache[$key] = $value;

        return $result !== false;
    }

    /**
     * Get all settings as key-value pairs
     */
    public function getAllAsArray(): array
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }

        return $result;
    }

    /**
     * Get multiple settings by keys
     */
    public function getMultiple(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

    /**
     * Set multiple settings at once
     */
    public function setMultiple(array $settings): bool
    {
        $this->db->transStart();

        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Check if voice is enabled
     */
    public function isVoiceEnabled(): bool
    {
        return (bool) $this->get('voice_enabled', true);
    }

    /**
     * Get voice volume
     */
    public function getVoiceVolume(): float
    {
        return (float) $this->get('voice_volume', 0.8);
    }

    /**
     * Get display count
     */
    public function getDisplayCount(): int
    {
        return (int) $this->get('display_count', 5);
    }

    /**
     * Get auto refresh interval
     */
    public function getAutoRefreshInterval(): int
    {
        return (int) $this->get('auto_refresh_interval', 5);
    }

    /**
     * Get max recall count
     */
    public function getRecallMax(): int
    {
        return (int) $this->get('recall_max', 3);
    }

    /**
     * Check if kiosk should show name input
     */
    public function kioskShowName(): bool
    {
        return (bool) $this->get('kiosk_show_name', false);
    }

    /**
     * Get reset time
     */
    public function getResetTime(): string
    {
        return $this->get('reset_time', '00:00');
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        $this->cache = [];
    }
}
