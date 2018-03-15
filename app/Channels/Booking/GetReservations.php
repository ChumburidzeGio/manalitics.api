<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/13/18
 * Time: 4:04 PM
 */

class GetReservations
{
    protected $reservation = [];

    /**
     * @param $answer
     * @return mixed
     */
    public function set($key, $value)
    {
        $this->reservation[$key] = $value;
    }

        /**
         * @param $answer
         * @return mixed
         */
        public function call($answer)
    {
//        if (!$answer['success']) {
//            return $answer['errors'];
//        }
//
//        $reservations = [];
//
//        if (!$answer['obj']) {
//            return compact('reservations');
//        }
//
//        foreach ($answer['obj']->reservation as $one) {

            $reservation['res_id'] = (string)$one->id;

            switch ((string)$one->status) {
                case 'new':
                    $reservation['status'] = 'booked';
                    break;
                case 'modified':
                    $reservation['status'] = 'booked';
                    $reservation['modified'] = 1;
                    break;
                case 'cancelled':
                    $reservation['status'] = 'cancelled';
                    if ($one->total_cancellation_fee) {
                        $reservation['res_cancel_fee'] = number_format((string)$one->total_cancellation_fee, 2);
                    }
                    $reservations[] = $reservation;
                    continue 2;
                    break;
            }

            $reservation['res_created'] = (string)$one->date . ' ' . (string)$one->time;
            $reservation['res_source'] = 'Booking.com';

            if ($one->loyalty_id) {
                $reservation['res_loyalty_id'] = (string)$one->loyalty_id;
            }

            $reservation['commission'] = number_format((string)$one->commissionamount, 2);
            $reservation['buyer_firstname'] = (string)$one->customer->first_name;
            $reservation['buyer_lastname'] = (string)$one->customer->last_name;
            $reservation['email'] = (string)$one->customer->email;
            $reservation['phone'] = (string)$one->customer->telephone;
            $reservation['total'] = number_format((string)$one->totalprice, 2);
            $reservation['currency'] = (string)$one->currencycode;

            if ($one->customer->remarks) {
                $reservation['comments'] = (string)$one->customer->remarks;
                //genius
                if (strpos($reservation['comments'], '*** Genius booker ***') !== false) {
                    if (!$reservation['res_loyalty_id']) {
                        $reservation['res_loyalty_id'] = '';
                    }
                    $reservation['res_loyalty_id'] .= '*** Genius booker ***';
                    $reservation['comments'] = str_replace('*** Genius booker ***', '', $reservation['comments']);
                }
            }

            if ($one->customer->cc_number) {
                $reservation['cc_details'] = [
                    "card_type" => (string)$one->customer->cc_type,
                    "card_number" => (string)$one->customer->cc_number,
                    "card_name" => (string)$one->customer->cc_name,
                    "card_expiry_month" => substr((string)$one->customer->cc_expiration_date, 0, 2),
                    "card_expiry_year" => substr((string)$one->customer->cc_expiration_date, -2, 2),
                    "card_cvv" => (string)$one->customer->cc_cvc,
                    "dc_issue_number" => (string)$one->customer->dc_issue_number,
                    "dc_start_date" => (string)$one->customer->dc_start_date,
                ];
            }

            $reservation["address"] = (string)$one->customer->address;
            $reservation["city"] = (string)$one->customer->city;
            $reservation["country"] = (string)$one->customer->countrycode;
            $reservation["postal_code"] = (string)$one->customer->zip;
            $reservation["state"] = '';
            $reservation['rooms'] = $this->rooms($one->room);
            $reservations[] = $reservation;

        //}

       // return compact('reservations');
    }

    public function rooms($rooms)
    {
        if (!$rooms) {
            return [];
        }

        foreach ($rooms as $room) {

            $rrRoom = [];
            $rrRoom['rr_id'] = (string)$room->roomreservation_id;
            $rrRoom['inventory'] = (string)$room->id;
            $rrRoom['plan'] = (string)$room->price['rate_id'];

            foreach ($room->price as $price) {
                $rrRoom['prices'][] = (string)$price;
            }

            if ($rrRoom['prices']) {
                $rrRoom['prices'] = implode(',', $rrRoom['prices']);
            }

            $rrRoom['commission'] = number_format((string)$room->commissionamount, 2);
            $rrRoom['currency'] = (string)$room->currencycode;
            $rrRoom['total'] = number_format((string)$room->totalprice, 2);
            $reservation['date_arrival'] = (string)$room->arrival_date;
            $reservation['date_departure'] = (string)$room->departure_date;
            $str = trim($room->guest_name);

            $firstName = $lastName = null;

            if ($str != "") {
                $str = trim(preg_replace("/[[:blank:]]+/", " ", $str));
                list($firstName, $lastName) = explode(" ", $str, 2);
            }

            if (!$firstName || !$lastName) {
                $firstName = (string)$one->customer->first_name;
                $lastName = (string)$one->customer->last_name;
            }

            $rrRoom['guest_firstname'] = $firstName;
            $rrRoom['guest_lastname'] = $lastName;
            $rrRoom['count_adult'] = (int)$room->numberofguests;
            $rrRoom['count_child'] = 0;
            $comments = '';

            if ($room->addons) {
                foreach ($room->addons->addon as $addon) {
                    $comments .= "- " . $room->currencycode . ' ' . number_format((string)$addon->totalprice, 2) . ' ' . $addon->name . ' for ' . $addon->nights . ' night(s) and ' . $addon->persons . ' person(s)' . "\n";
                }
            }

            if (!empty($comments)) {
                $comments = "Add-ons:\n" . $comments . "\n";
            }

            $comments .= "Smoking room: " . ($room->smoking == 0 ? "No" : "Yes") . "\n";

            if ($room->remarks) {
                $comments .= (string)$room->remarks;
            }

            $rrRoom['comments'] = $comments;
            $reservation['rooms'][] = $rrRoom;
        }
    }
}