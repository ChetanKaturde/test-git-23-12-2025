<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\City;

class StateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stateCityData = [
            'Andhra Pradesh' => [
                'Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool',
                'Rajahmundry', 'Tirupati', 'Kadapa', 'Kakinada', 'Anantapur',
                'Eluru', 'Machilipatnam', 'Chittoor', 'Tenali', 'Ongole'
            ],
            'Arunachal Pradesh' => [
                'Itanagar', 'Naharlagun', 'Pasighat', 'Tezpur', 'Bomdila',
                'Ziro', 'Along', 'Tezu', 'Changlang', 'Roing'
            ],
            'Assam' => [
                'Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon',
                'Tinsukia', 'Tezpur', 'Bongaigaon', 'Dhubri', 'North Lakhimpur',
                'Karimganj', 'Sivasagar'
            ],
            'Bihar' => [
                'Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia',
                'Darbhanga', 'Bihar Sharif', 'Arrah', 'Begusarai', 'Katihar',
                'Chapra', 'Sasaram'
            ],
            'Chhattisgarh' => [
                'Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg',
                'Rajnandgaon', 'Jagdalpur', 'Raigarh', 'Ambikapur', 'Dhamtari'
            ],
            'Goa' => [
                'Panaji', 'Vasco da Gama', 'Margao', 'Mapusa', 'Ponda',
                'Bicholim', 'Curchorem', 'Sanquelim', 'Canacona', 'Valpoi'
            ],
            'Gujarat' => [
                'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar',
                'Jamnagar', 'Junagadh', 'Gandhinagar', 'Anand', 'Nadiad',
                'Morbi', 'Mehsana'
            ],
            'Haryana' => [
                'Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar',
                'Rohtak', 'Hisar', 'Karnal', 'Sonipat', 'Panchkula',
                'Sirsa', 'Rewari'
            ],
            'Himachal Pradesh' => [
                'Shimla', 'Dharamshala', 'Solan', 'Mandi', 'Palampur',
                'Baddi', 'Nahan', 'Paonta Sahib', 'Sundernagar', 'Chamba',
                'Bilaspur'
            ],
            'Jharkhand' => [
                'Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro Steel City', 'Deoghar',
                'Phusro', 'Hazaribagh', 'Giridih', 'Ramgarh', 'Medininagar',
                'Chaibasa'
            ],
            'Karnataka' => [
                'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum',
                'Gulbarga', 'Davanagere', 'Bellary', 'Bijapur', 'Shimoga',
                'Tumkur', 'Raichur'
            ],
            'Kerala' => [
                'Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam',
                'Palakkad', 'Alappuzha', 'Malappuram', 'Kannur', 'Kasaragod',
                'Kottayam', 'Pathanamthitta'
            ],
            'Madhya Pradesh' => [
                'Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain',
                'Sagar', 'Dewas', 'Satna', 'Ratlam', 'Rewa',
                'Chhindwara', 'Khargone'
            ],
            'Maharashtra' => [
                'Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik',
                'Aurangabad', 'Solapur', 'Amravati', 'Kolhapur', 'Sangli',
                'Nanded', 'Jalgaon', 'Akola', 'Latur', 'Dhule',
                'Chandrapur', 'Parbhani', 'Ahmednagar', 'Beed', 'Wardha',
                'Satara', 'Yavatmal', 'Bhiwandi', 'Ulhasnagar', 'Kalyan',
                'Panvel', 'Vasai-Virar', 'Malegaon', 'Ichalkaranji', 'Jalna'
            ],
            'Manipur' => [
                'Imphal', 'Bishnupur', 'Thoubal', 'Churachandpur', 'Ukhrul',
                'Senapati', 'Tamenglong', 'Chandel', 'Moreh'
            ],
            'Meghalaya' => [
                'Shillong', 'Tura', 'Cherrapunji', 'Jowai', 'Baghmara',
                'Nongpoh', 'Mawkyrwat', 'Resubelpara', 'Williamnagar'
            ],
            'Mizoram' => [
                'Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib',
                'Serchhip', 'Mamit', 'Lawngtlai', 'Saitual'
            ],
            'Nagaland' => [
                'Kohima', 'Dimapur', 'Mokokchung', 'Tuensang', 'Wokha',
                'Zunheboto', 'Phek', 'Kiphire', 'Longleng', 'Peren', 'Mon'
            ],
            'Odisha' => [
                'Bhubaneswar', 'Cuttack', 'Rourkela', 'Brahmapur', 'Sambalpur',
                'Puri', 'Balasore', 'Bhadrak', 'Baripada', 'Jharsuguda',
                'Angul'
            ],
            'Punjab' => [
                'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda',
                'Mohali', 'Firozpur', 'Batala', 'Pathankot', 'Moga',
                'Hoshiarpur'
            ],
            'Rajasthan' => [
                'Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer',
                'Udaipur', 'Bhilwara', 'Alwar', 'Bharatpur', 'Sikar',
                'Tonk'
            ],
            'Sikkim' => [
                'Gangtok', 'Namchi', 'Geyzing', 'Mangan', 'Jorethang',
                'Nayabazar', 'Singtam', 'Rangpo', 'Soreng'
            ],
            'Tamil Nadu' => [
                'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem',
                'Tirunelveli', 'Tiruppur', 'Vellore', 'Erode', 'Thoothukkudi',
                'Dindigul', 'Nagercoil'
            ],
            'Telangana' => [
                'Hyderabad', 'Warangal', 'Nizamabad', 'Khammam', 'Karimnagar',
                'Ramagundam', 'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Suryapet',
                'Miryalaguda'
            ],
            'Tripura' => [
                'Agartala', 'Dharmanagar', 'Udaipur', 'Kailashahar', 'Belonia',
                'Khowai', 'Amarpur', 'Teliamura', 'Sonamura'
            ],
            'Uttar Pradesh' => [
                'Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Varanasi',
                'Meerut', 'Allahabad', 'Bareilly', 'Aligarh', 'Moradabad',
                'Noida', 'Gorakhpur'
            ],
            'Uttarakhand' => [
                'Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur',
                'Kashipur', 'Rishikesh', 'Pithoragarh', 'Jaspur', 'Kichha',
                'Tehri'
            ],
            'West Bengal' => [
                'Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri',
                'Malda', 'Bardhaman', 'Kharagpur', 'Haldia', 'Raiganj',
                'Berhampore'
            ],
            'Andaman and Nicobar Islands' => [
                'Port Blair', 'Diglipur', 'Mayabunder', 'Rangat', 'Car Nicobar',
                'Hut Bay'
            ],
            'Chandigarh' => ['Chandigarh'],
            'Dadra and Nagar Haveli and Daman and Diu' => [
                'Daman', 'Diu', 'Silvassa', 'Amli'
            ],
            'Delhi' => [
                'New Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 'West Delhi',
                'Central Delhi', 'North East Delhi', 'North West Delhi', 'South East Delhi',
                'South West Delhi', 'Shahdara'
            ],
            'Jammu and Kashmir' => [
                'Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore',
                'Kathua', 'Udhampur', 'Punch', 'Rajouri', 'Kupwara'
            ],
            'Ladakh' => [
                'Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Diskit'
            ],
            'Lakshadweep' => [
                'Kavaratti', 'Agatti', 'Minicoy', 'Amini', 'Andrott', 'Kalpeni'
            ],
            'Puducherry' => [
                'Puducherry', 'Karaikal', 'Mahe', 'Yanam', 'Ozhukarai'
            ]
        ];

        foreach ($stateCityData as $stateName => $cities) {
            $state = State::create(['name' => $stateName]);
            
            foreach ($cities as $cityName) {
                City::create([
                    'name' => $cityName,
                    'state_id' => $state->id
                ]);
            }
        }
    }
}
