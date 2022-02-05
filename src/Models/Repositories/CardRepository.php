<?php

namespace WalkerChiu\DeviceRFID\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class CardRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.device-rfid.card'));
    }

    /**
     * @param String  $code
     * @param Array   $data
     * @param Bool    $is_enabled
     * @param Bool    $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(string $code, array $data, $is_enabled = null, $auto_packing = false)
    {
        $instance = $this->instance;
        if ($is_enabled === true)      $instance = $instance->ofEnabled();
        elseif ($is_enabled === false) $instance = $instance->ofDisabled();

        $data = array_map('trim', $data);
        $repository = $instance->with(['langs' => function ($query) use ($code) {
                                    $query->ofCurrent()
                                          ->ofCode($code);
                                }])
                                ->whereHas('langs', function ($query) use ($code) {
                                    return $query->ofCurrent()
                                                 ->ofCode($code);
                                })
                                ->unless(empty(config('wk-core.class.morph-tag.tag')), function ($query) {
                                    return $query->with(['tags', 'tags.langs']);
                                })
                                ->when($data, function ($query, $data) {
                                    return $query->unless(empty($data['id']), function ($query) use ($data) {
                                                return $query->where('id', $data['id']);
                                            })
                                            ->unless(empty($data['reader_id']), function ($query) use ($data) {
                                                return $query->where('reader_id', $data['reader_id']);
                                            })
                                            ->unless(empty($data['user_id']), function ($query) use ($data) {
                                                return $query->where('user_id', $data['user_id']);
                                            })
                                            ->unless(empty($data['status_id']), function ($query) use ($data) {
                                                return $query->where('status_id', $data['status_id']);
                                            })
                                            ->unless(empty($data['level_id']), function ($query) use ($data) {
                                                return $query->where('level_id', $data['level_id']);
                                            })
                                            ->unless(empty($data['serial']), function ($query) use ($data) {
                                                return $query->where('serial', $data['serial']);
                                            })
                                            ->unless(empty($data['identifier']), function ($query) use ($data) {
                                                return $query->where('identifier', $data['identifier']);
                                            })
                                            ->unless(empty($data['username']), function ($query) use ($data) {
                                                return $query->where('username', $data['username']);
                                            })
                                            ->when(isset($data['is_black']), function ($query) use ($data) {
                                                return $query->where('is_black', $data['is_black']);
                                            })
                                            ->unless(empty($data['begin_at']), function ($query) use ($data) {
                                                return $query->where('begin_at', $data['begin_at']);
                                            })
                                            ->unless(empty($data['end_at']), function ($query) use ($data) {
                                                return $query->where('end_at', $data['end_at']);
                                            })
                                            ->unless(empty($data['only_dayType']), function ($query) use ($data) {
                                                return $query->where('only_dayType', $data['only_dayType']);
                                            })
                                            ->unless(empty($data['exclude_date']), function ($query) use ($data) {
                                                return $query->where('exclude_date', $data['exclude_date']);
                                            })
                                            ->unless(empty($data['exclude_time']), function ($query) use ($data) {
                                                return $query->where('exclude_time', $data['exclude_time']);
                                            })
                                            ->unless(empty($data['name']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'name')
                                                          ->where('value', 'LIKE', "%".$data['name']."%");
                                                });
                                            })
                                            ->unless(empty($data['description']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'description')
                                                          ->where('value', 'LIKE', "%".$data['description']."%");
                                                });
                                            })
                                            ->unless(empty($data['categories']), function ($query) use ($data) {
                                                return $query->whereHas('categories', function ($query) use ($data) {
                                                    $query->ofEnabled()
                                                          ->whereIn('id', $data['categories']);
                                                });
                                            })
                                            ->unless(empty($data['tags']), function ($query) use ($data) {
                                                return $query->whereHas('tags', function ($query) use ($data) {
                                                    $query->ofEnabled()
                                                          ->whereIn('id', $data['tags']);
                                                });
                                            });
                                })
                                ->orderBy('updated_at', 'DESC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-device-rfid.output_format'), config('wk-device-rfid.pagination.pageName'), config('wk-device-rfid.pagination.perPage'));
            $factory->setFieldsLang(['name', 'description']);
            return $factory->output($repository);
        }

        return $repository;
    }

    /**
     * @param Card          $instance
     * @param Array|String  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
    }
}
