<?php

use App\Models\Cluster;
use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data = [
            'Ayala Corporation Group' => [
                'AYALA CORPORATION',
                'AFFINITYX',
                'AVIATION',
                'AYALA FOUNDATION',
                'AG LEGAL',
                'ZALORA',
                'SONOMA',
                'Ayala Cooperatives',
                'APEC SCHOOLS - iPeople',
                'NATIONAL TEACHERS COLLEGE - iPeople'
            ],
            'ACEN Group' => [
                'ACEN CORPORATION',
                'AC ENERGY INFRASTRUCTURE CORPORATION',
                'ACE THERMAL, INC.',
                'ACEN RENEWABLES INTERNATIONAL PTE. LTD.',
                'ACEN VIETNAM COMPANY LIMITED THEN',
                'ACEN VIETNAM INVESTMENTS PTE. LTD.',
                'ACEN INVESTMENTS HK LIMITED',
                'ACEN AUSTRALIA PTY LTD',
                'ACE SHARED SERVICES, INC.',
                'SOUTH LUZON THERMAL ENERGY CORPORATION',
                'BULACAN POWER GENERATION CORPORATION',
                'CIP II POWER CORPORATION',
                'ONE SUBIC POWER GENERATION CORP.',
                'INGRID POWER HOLDINGS, INC.',
                'GUIMARAS WIND POWER CORPORATION',
                'NORTH LUZON RENEWABLE ENERGY CORP.',
                'NORTHWIND POWER DEVELOPMENT CORPORATION',
                'BATAAN SOLAR ENERGY, INC.',
                'GIGA ACE 4, INC.',
                'GIGASOL3, INC.',
                'MONTE SOLAR ENERGY, INC.',
                'NEGROS ISLAND SOLAR POWER INC.',
                'SAN CARLOS SOLAR ENERGY, INC.',
                'SOLARACE1 ENERGY, INC.',
                'HDP BULK WATER SUPPLY, INC.',
                'MCV BULK WATER SUPPLY, INC.',
                'LCC BULK WATER SUPPLY, INC.',
                'SCC BULK WATER SUPPLY, INC.'
            ],
            'AC Health Group' => [
                'AYALA HEALTHCARE HOLD CO.',
                'GENERIKA',
                'IE MEDICA',
                'MED ETHIX',
                'HEALTHWAY PHILIPPINES, INC.',
                'HEALTHWAY CANCER CARE HOSPITAL',
                'HMC, INC.',
                'QUALIMED NETWORK',
                'AHCHI PHARMA VENTURES, INC.'
            ],
            'AC Industrials Group' => [
                'INTEGRATED MICRO-ELECTRONICS',
                'HONDA CARS MAKATI, INC.',
                'HONDA CARS CEBU, INC.',
                'ISUZU AUTOMOTIVE DEALERSHIP, INC.',
                'ISUZU CEBU, INC.',
                'ISUZU ILOILO CORPORATION',
                'ICONIC DEALERSHIP INC. (4-WHEEL)',
                'ICONIC DEALERSHIP INC. (2-WHEEL)',
                'AUTOMOBILE CENTRAL ENTERPRISE, INC.',
                'AC AUTOMOTIVE BUSINESS SERVICES, INC.',
                'ADVENTURE CYCLE PHILIPPINES, INC.',
                'KP MOTORS CORPORATION',
                'KTM ASIA MOTORCYCLE MANUFATURING, INC.',
                'ACI HOLDINGS'
            ],
            'AC Logistics Group' => [
                'AC INFRASTRUCTURE HOLDINGS CORPORATION',
                'AC LOGISTICS HOLDINGS CORPORATION',
                'AIR 21 HOLDINGS, INC.',
                'CARGOHAUS, INC.',
                'AIRFREIGHT 2100, INCORPORATED',
                'U-FREIGHT PHILS., INC.',
                'U-OCEAN, INC.',
                'INTEGRATED WASTE MANAGEMENT., INC.',
                'LGC LOGISTICS, INC.',
                'WASTE & RESOURCE MANAGEMENT., INC.',
                'AIR21 SUBIC',
                'AF PAYMENTS, INC.'
            ],
            'Ayala Land Group' => [
                'AYALA LAND, INC.',
                'AYALALAND PREMIER INC.',
                'ALVEO LAND CORP',
                'AVIDA LAND CORP',
                'AMAIA LAND CORP',
                'BELLAVITA LAND CORP',
                'AYALA LAND SALES INC.',
                'AYALA LAND INTERNATIONAL SALES INC.',
                'AMICASSA PROCESS SOLUTIONS INC.',
                'AYALALAND MALLS INC.',
                'AYALALAND MALLS VISMIN INC',
                'AYALALAND OFFICES INC.',
                'AYALALAND LOGISTICS HOLDINGS CORP',
                'AYALALAND HOTELS AND RESORTS CORP',
                'TEN KNOTS DEVELOPMENT CORP',
                'FAIRMONT AND RAFFLES',
                'HOLIDAY INN AND SUITES',
                'SEDA BGC',
                'SEDA NUVALI',
                'SEDA RESIDENCES MAKATI',
                'SEDA VERTIS NORTH',
                'SEDA MANILA BAY',
                'SEDA AYALA CENTER CEBU',
                'SEDA CENTRAL BLOC',
                'SEDA CAPITOL CENTRAL',
                'SEDA ABREEZA',
                'SEDA CENTRIO',
                'SEDA LIO',
                'SEDA ATRIA',
                'AYALALAND ESTATES INC',
                'CAGAYAN DE ORO GATEWAY CORP',
                'ACCENDO',
                'ALI CAPITAL CORP.',
                'DIRECTPOWER',
                'AIRSWIFT',
                'SWIFT AERODOME',
                'AYALA PROPERTY MANAGEMENT CORP.',
                'PRIME SUPPORT SERVICES INC.',
                'MAKATI DEVELOPMENT CORP.',
                'MDC BUILDPLUS',
                'MDC EQUIPMENT',
                'MDC CONQRETE',
                'MDBI',
                'APRISA'
            ],
            'BPI Group' => [
                'BANK OF THE PHILIPPINE ISLANDS - Main Company',
                'AYALA PLANS INC.',
                'BPI CAPITAL CORP.',
                'BPI ASSET MGMT AND TRUST, CORP',
                'BPI INVESTMENT MGT. INC.',
                'BPI DIRECT BANKO',
                'BPI SECURITIES CORP.',
                'Rbank'
            ],
            'Globe Group' => [
                '917VENTURES',
                'ASTICOM GROUP',
                'BRAVE CONNECTIVE',
                'CASCADEO',
                'ECPAY',
                'GCASH/MYNT',
                'GLOBAL TELEHEALTH',
                'GLOBE TELECOM',
                'HEALTHNOW',
                'KICKSTART',
                'KODEGO',
                'KONSULTA MD'
            ]
        ];

        foreach ($data as $cluster => $company) {

            $cluster_id = Cluster::insertGetId([
                'name' => $cluster,
                'created_at' => now(),
            ]);

            foreach ($company as $name) {
                Company::insert([
                    'name' => $name,
                    'cluster_id' => $cluster_id,
                    'created_at' => now()
                ]);

            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clusters_and_companies', function (Blueprint $table) {
            //
        });
    }
};
