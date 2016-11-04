<?php

namespace BrianFaust\Addressable\Traits;

use BrianFaust\Addressable\Models\Address;
use Illuminate\Support\Collection;

trait Addressable
{
    /**
     * @return mixed
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * @param null $address
     *
     * @return mixed
     */
    public function primaryAddress($address = null)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 1, 'is_billing' => 0, 'is_shipping' => 0,
            ]);
        }

        return $this->addresses()->orderBy('is_primary', 'DESC')->firstOrFail();
    }

    /**
     * @param null $address
     *
     * @return mixed
     */
    public function billingAddress($address = null)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 0, 'is_billing' => 1, 'is_shipping' => 0,
            ]);
        }

        return $this->addresses()->orderBy('is_billing', 'DESC')->firstOrFail();
    }

    /**
     * @param null $address
     *
     * @return mixed
     */
    public function shippingAddress($address = null)
    {
        if (!empty($address)) {
            $address->update([
                'is_primary' => 0, 'is_billing' => 0, 'is_shipping' => 1,
            ]);
        }

        return $this->addresses()->orderBy('is_shipping', 'DESC')->firstOrFail();
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function createAddress($data)
    {
        return $this->addresses()->save(new Address($data));
    }

    /**
     * @param $address
     * @param $data
     *
     * @return mixed
     */
    public function updateAddress($address, $data)
    {
        return $address->update($data);
    }

    /**
     * @param $address
     *
     * @return mixed
     */
    public function deleteAddress($address)
    {
        return $address->delete();
    }

    /**
     * @param $distance
     * @param $type
     * @param $lat
     * @param $lng
     *
     * @return Collection
     */
    public static function findByDistance($distance, $type, $lat, $lng)
    {
        $records = Address::within($distance, $type, $lat, $lng)->get();

        $results = [];
        foreach ($records as $record) {
            $results[] = $record->addressable;
        }

        return new Collection($results);
    }
}
