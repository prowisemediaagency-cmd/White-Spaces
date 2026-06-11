<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Sector;
use Illuminate\Database\Seeder;

/**
 * Base transcrita do levantamento "Rolling Informa Events List" fornecido.
 *
 * Correcoes de dado aplicadas (pais definido pelo venue):
 *  - Abastur -> MX (estava sob EUA)
 *  - Toronto International Boat Show -> CA (estava sob EUA)
 *  - Global Medtech Connect (New Delhi) -> IN (estava sob EUA)
 *  - Transcontinental Trusts: Bermuda -> BM (estava sob EUA)
 *  - Energy Storage Summit LATAM -> CL apenas (estava duplicado em BR e CL)
 *  - Edmonton Expo deduplicado (aparecia em CA e EUA)
 *  - AeroEngines Americas (2027) descartado: a fonte chegou truncada nessa linha.
 *
 * Formato: [nome, ano, divisao, tipo, status, inicio, fim, pais, regiao, venue, cidade, setor-slug]
 */
class EventSeeder extends Seeder
{
    private const DIVISIONS = ['M' => 'Informa Markets', 'C' => 'Informa Connect', 'F' => 'Informa Festivals'];

    private const EVENTS = [
        // ----- Australia -----
        ['CAPA Airline Leader Summit - Australia Pacific', 2026, 'M', 'Conference', 'Scheduled', '2026-07-28', '2026-07-29', 'AU', 'Australia', 'Adelaide Oval', 'Adelaide', 'aviacao'],
        ['Battery Asset Management Summit Australia', 2026, 'M', 'Conference', 'Scheduled', '2026-08-25', '2026-08-26', 'AU', 'Australia', 'Amora Hotel Jamison Sydney', 'Sydney', 'energia'],
        ['MRO Australasia', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-11', '2026-11-12', 'AU', 'Australia', 'Brisbane Convention & Exhibition Centre', 'Brisbane', 'aviacao'],
        // ----- Bahrein -----
        ['Jewellery Arabia', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-24', '2026-11-28', 'BH', 'Middle East', 'Exhibition World Bahrain', 'Manama', 'joias'],
        ['Scent Arabia', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-24', '2026-11-28', 'BH', 'Middle East', 'Exhibition World Bahrain', 'Manama', 'beleza'],
        ['Cityscape Bahrain', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-24', '2026-11-28', 'BH', 'Middle East', 'Exhibition World Bahrain', 'Manama', 'real-estate'],
        // ----- Belgica -----
        ['CompLaw: Merger Control', 2026, 'C', 'Conference', 'Scheduled', '2026-10-01', '2026-10-01', 'BE', 'Europe', "Steigenberger Icon Wiltcher's", 'Bruxelas', 'juridico'],
        ['AI Regulation Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-09-22', '2026-09-23', 'BE', 'Europe', 'DoubleTree by Hilton Brussels City', 'Bruxelas', 'tech'],
        // ----- Brasil -----
        ['Fispal Tecnologia', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-16', '2026-06-19', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'embalagem'],
        ['TecnoCarne', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-16', '2026-06-19', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'alimentos'],
        ['ABF Franchising Expo', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-24', '2026-06-27', 'BR', 'South America', 'Expo Center Norte', 'Sao Paulo', 'franchising'],
        ['Biocontrol & Biostimulant LATAM', 2026, 'C', 'Conference', 'Scheduled', '2026-07-28', '2026-07-29', 'BR', 'South America', 'Royal Palm Hall', 'Campinas', 'agro'],
        ['Fi South America', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-04', '2026-08-06', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'ingredientes'],
        ['Concrete Show South America', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-25', '2026-08-27', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'construcao'],
        ['Healthcare Innovation Show', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-16', '2026-09-17', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'saude-medtech'],
        ['Brazil Windpower', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-27', '2026-10-29', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'energia'],
        ['Intermodal South America', 2027, 'M', 'Trade Show', 'Scheduled', '2027-04-13', '2027-04-15', 'BR', 'South America', 'Distrito Anhembi', 'Sao Paulo', 'logistica'],
        ['Energy Solutions Show', 2027, 'M', 'Trade Show', 'Scheduled', '2027-04-14', '2027-04-15', 'BR', 'South America', 'Distrito Anhembi', 'Sao Paulo', 'energia'],
        ['FEIMEC - International Machinery and Equipment Fair', 2028, 'M', 'Trade Show', 'Scheduled', '2028-05-09', '2028-05-13', 'BR', 'South America', 'Sao Paulo Expo', 'Sao Paulo', 'manufatura'],
        // ----- Chile (deduplicado de BR) -----
        ['Energy Storage Summit Latin America', 2026, 'M', 'Conference', 'Scheduled', '2026-10-27', '2026-10-28', 'CL', 'South America', 'InterContinental Santiago', 'Santiago', 'energia'],
        // ----- Camboja -----
        ['CAMWATER', 2027, 'M', 'Trade Show', 'Scheduled', '2027-09-15', '2027-09-17', 'KH', 'Asia', 'Diamond Island Convention & Exhibition Center', 'Phnom Penh', 'agua'],
        ['CAMFOOD & CAMHOTEL', 2027, 'M', 'Trade Show', 'Scheduled', '2027-11-03', '2027-11-05', 'KH', 'Asia', 'Diamond Island Convention & Exhibition Center', 'Phnom Penh', 'alimentos'],
        // ----- Canada -----
        ['FAN EXPO Canada', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-08-27', '2026-08-30', 'CA', 'North America', 'Metro Toronto Convention Centre', 'Toronto', 'pop-culture'],
        ['RealREIT', 2026, 'C', 'Conference', 'Scheduled', '2026-09-09', '2026-09-09', 'CA', 'North America', 'Metro Toronto Convention Centre', 'Toronto', 'real-estate'],
        ['Quebec City Real Estate Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-09-29', '2026-09-29', 'CA', 'North America', 'Centre des congres de Quebec', 'Quebec City', 'real-estate'],
        ['Canadian Apartment Investment Conference', 2026, 'C', 'Conference', 'Scheduled', '2026-09-10', '2026-09-10', 'CA', 'North America', 'Metro Toronto Convention Centre', 'Toronto', 'real-estate'],
        ['Edmonton Expo', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-09-18', '2026-09-20', 'CA', 'North America', 'Edmonton EXPO Centre', 'Edmonton', 'pop-culture'],
        ['Construction IMPEX Canada', 2026, 'C', 'Trade Show', 'Scheduled', '2026-10-28', '2026-10-29', 'CA', 'North America', 'E.Y. Centre', 'Ottawa', 'construcao'],
        ['Art Toronto', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-10-29', '2026-11-01', 'CA', 'North America', 'Metro Toronto Convention Centre', 'Toronto', 'arte'],
        ['One of a Kind Winter Market', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-11-27', '2026-12-06', 'CA', 'North America', 'Enercare Centre', 'Toronto', 'arte'],
        ['Advanced Design & Manufacturing Montreal', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-11', '2026-11-12', 'CA', 'North America', 'Palais des congres de Montreal', 'Montreal', 'manufatura'],
        ['BUILDEX Vancouver', 2027, 'C', 'Trade Show', 'Scheduled', '2027-02-10', '2027-02-11', 'CA', 'North America', 'Vancouver Convention Centre', 'Vancouver', 'construcao'],
        ['FAN EXPO Vancouver', 2027, 'C', 'Consumer Show', 'Scheduled', '2027-02-13', '2027-02-15', 'CA', 'North America', 'Vancouver Convention Centre', 'Vancouver', 'pop-culture'],
        ['LBMAO Buying Show', 2027, 'C', 'Confex', 'Scheduled', '2027-03-31', '2027-04-01', 'CA', 'North America', 'Toronto Congress Centre', 'Toronto', 'construcao'],
        ['Pharmaceutical Compliance Congress Canada', 2027, 'C', 'Conference', 'Scheduled', '2027-06-01', '2027-06-02', 'CA', 'North America', 'Omni King Edward Hotel', 'Toronto', 'farma'],
        ['Atlantic Real Estate Forum', 2027, 'C', 'Conference', 'Scheduled', '2027-11-01', '2027-11-01', 'CA', 'North America', 'Halifax Convention Centre', 'Halifax', 'real-estate'],
        ['Advanced Design & Manufacturing Toronto', 2027, 'M', 'Trade Show', 'Scheduled', '2027-11-09', '2027-11-11', 'CA', 'North America', 'Toronto Congress Centre', 'Toronto', 'manufatura'],
        ['Vancouver Real Estate Forum', 2027, 'C', 'Conference', 'Scheduled', '2027-04-07', '2027-04-08', 'CA', 'North America', 'Vancouver Convention Centre', 'Vancouver', 'real-estate'],
        ['Toronto International Boat Show', 2027, 'M', 'Consumer Show', 'Scheduled', '2027-01-16', '2027-01-24', 'CA', 'North America', 'Enercare Centre', 'Toronto', 'nautico'],
        // ----- Ilhas Cayman -----
        ['GAIM Ops Cayman', 2027, 'C', 'Confex', 'Scheduled', '2027-04-18', '2027-04-21', 'KY', 'North America', 'The Ritz-Carlton, Grand Cayman', 'Grand Cayman', 'real-estate'],
        // ----- China -----
        ['Starch Expo China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-15', '2026-06-17', 'CN', 'Asia', 'National Exhibition and Convention Center', 'Xangai', 'ingredientes'],
        ['Healthplex Expo Natural & Nutraceutical Products China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-15', '2026-06-17', 'CN', 'Asia', 'National Exhibition and Convention Center', 'Xangai', 'ingredientes'],
        ['Hi & Fi Asia-China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-15', '2026-06-17', 'CN', 'Asia', 'National Exhibition and Convention Center', 'Xangai', 'ingredientes'],
        ['ProPak China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-15', '2026-06-17', 'CN', 'Asia', 'National Exhibition and Convention Center', 'Xangai', 'embalagem'],
        ['CPHI China & PMEC China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-16', '2026-06-18', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'farma'],
        ['CBME China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-07-15', '2026-07-17', 'CN', 'Asia', 'National Exhibition and Convention Center', 'Xangai', 'materno'],
        ['CPHI Shenzhen & PMEC Shenzhen', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-31', '2026-09-02', 'CN', 'Asia', 'Shenzhen Convention & Exhibition Center', 'Shenzhen', 'farma'],
        ['Maison Shanghai', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-07', '2026-09-09', 'CN', 'Asia', 'Shanghai World Expo Exhibition & Convention Center', 'Xangai', 'moveis'],
        ['Furniture China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-08', '2026-09-11', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'moveis'],
        ['HOTELEX Shenzhen', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-13', '2026-10-15', 'CN', 'Asia', 'Shenzhen World Exhibition & Convention Center', 'Shenzhen', 'hotelaria'],
        ['ProPak Shenzhen', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-13', '2026-10-15', 'CN', 'Asia', 'Shenzhen World Exhibition & Convention Center', 'Shenzhen', 'embalagem'],
        ['The Lifestyle Show Shenzhen', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-13', '2026-10-15', 'CN', 'Asia', 'Shenzhen World Exhibition & Convention Center', 'Shenzhen', 'moveis'],
        ['Hotel & Shop Plus Shenzhen', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-13', '2026-10-15', 'CN', 'Asia', 'Shenzhen World Exhibition & Convention Center', 'Shenzhen', 'hotelaria'],
        ['H & R Design Expo', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-13', '2026-10-15', 'CN', 'Asia', 'Shenzhen World Exhibition & Convention Center', 'Shenzhen', 'hotelaria'],
        ['Shanghai International Franchise Exhibition - Autumn', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-10', '2026-11-12', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'franchising'],
        ['Food & Hospitality China', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-10', '2026-11-12', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'alimentos'],
        ['Salon du Chocolat', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-10', '2026-11-12', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'alimentos'],
        ['Cosmopack Asia Hong Kong', 2026, 'M', 'Trade Show', 'Scheduled', '2026-11-10', '2026-11-12', 'CN', 'Asia', 'AsiaWorld-Expo', 'Hong Kong', 'beleza'],
        ['LOUPE Asia', 2027, 'M', 'Trade Show', 'Scheduled', '2027-11-30', '2027-12-03', 'CN', 'Asia', 'Shanghai New International Expo Center', 'Xangai', 'joias'],
        // ----- Colombia -----
        ['AMWC Latin America', 2026, 'C', 'Trade Show', 'Scheduled', '2026-10-29', '2026-10-31', 'CO', 'South America', 'Plaza Mayor Convention Center', 'Medellin', 'estetica'],
        // ----- Emirados Arabes Unidos -----
        ['AGRAME', 2026, 'C', 'Conference', 'Scheduled', '2026-09-08', '2026-09-10', 'AE', 'Middle East', 'Dubai World Trade Centre', 'Dubai', 'agro'],
        ['SuperReturn CFO COO Middle East', 2026, 'C', 'Conference', 'Scheduled', '2026-10-13', '2026-10-15', 'AE', 'Middle East', 'The Ritz-Carlton, Dubai International Finance Centre', 'Dubai', 'real-estate'],
        ['HR Summit & Expo - HRSE Dubai', 2026, 'C', 'Trade Show', 'Scheduled', '2026-10-14', '2026-10-15', 'AE', 'Middle East', 'Dubai World Trade Centre', 'Dubai', 'rh'],
        ['Dubai Airshow', 2027, 'M', 'Trade Show', 'Scheduled', '2027-11-15', '2027-11-19', 'AE', 'Middle East', 'Dubai World Central - Al Maktoum International Airport', 'Dubai', 'aviacao'],
        // ----- Egito -----
        ['HR Summit & Expo - HRSE Egypt', 2026, 'C', 'Trade Show', 'Scheduled', '2026-11-24', '2026-11-25', 'EG', 'Middle East', 'Dusit Thani Lakeview Cairo', 'Cairo', 'rh'],
        ['ProPak MENA', 2027, 'M', 'Trade Show', 'Scheduled', '2027-06-01', '2027-06-03', 'EG', 'Africa', 'Egypt International Exhibition Center', 'Cairo', 'embalagem'],
        ['Fi Africa', 2027, 'M', 'Trade Show', 'Scheduled', '2027-06-01', '2027-06-03', 'EG', 'Africa', 'Egypt International Exhibition Center', 'Cairo', 'ingredientes'],
        // ----- Espanha -----
        ['Seatrade Cruise Med', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-16', '2026-09-17', 'ES', 'Europe', 'Las Palmas Cruise Port', 'Gran Canaria', 'maritimo'],
        ['LOUPE Europe', 2027, 'M', 'Trade Show', 'Scheduled', '2027-10-05', '2027-10-08', 'ES', 'Europe', 'Fira Barcelona Gran Via', 'Barcelona', 'joias'],
        // ----- Mexico (corrigido: estava sob EUA) -----
        ['Abastur', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-26', '2026-08-28', 'MX', 'North America', 'Centro Banamex', 'Cidade do Mexico', 'hotelaria'],
        // ----- India (corrigido: estava sob EUA) -----
        ['Global Medtech Connect', 2026, 'M', 'Conference', 'Scheduled', '2026-08-21', '2026-08-22', 'IN', 'Asia', 'Bharat Mandapam', 'Nova Delhi', 'saude-medtech'],
        // ----- Bermudas (corrigido: estava sob EUA) -----
        ['Transcontinental Trusts: Bermuda', 2026, 'C', 'Conference', 'Scheduled', '2026-06-17', '2026-06-19', 'BM', 'North America', 'Hamilton Princess', 'Hamilton', 'real-estate'],
        // ----- Estados Unidos -----
        ['Waste Leadership Summit', 2026, 'M', 'Conference', 'Traded', '2026-06-08', '2026-06-10', 'US', 'North America', 'Washington Hilton', 'Washington DC', 'residuos'],
        ['Compounding Pharmacy Compliance', 2026, 'C', 'Conference', 'Traded', '2026-06-09', '2026-06-10', 'US', 'North America', "Hilton Philadelphia at Penn's Landing", 'Filadelfia', 'farma'],
        ['Mezzanine Financing & High Yield Debt', 2026, 'C', 'Conference', 'Traded', '2026-06-09', '2026-06-09', 'US', 'North America', 'Union League Club', 'Nova York', 'real-estate'],
        ['Wealth Management EDGE', 2026, 'C', 'Conference', 'Active', '2026-06-09', '2026-06-11', 'US', 'North America', 'The Boca Raton', 'Boca Raton', 'real-estate'],
        ['CSP Foodservice Forum', 2026, 'C', 'Conference', 'Active', '2026-06-10', '2026-06-12', 'US', 'North America', 'Renaissance Schaumburg Convention Center Hotel', 'Schaumburg', 'varejo'],
        ['Litigation Finance US', 2026, 'C', 'Conference', 'Traded', '2026-06-10', '2026-06-10', 'US', 'North America', 'Union League Club', 'Nova York', 'juridico'],
        ['Windy City Boat & Yacht Show', 2026, 'M', 'Trade Show', 'Active', '2026-06-11', '2026-06-14', 'US', 'North America', 'Burnham Harbor', 'Chicago', 'nautico'],
        ['Real Estate Family Office & Private Wealth West', 2026, 'C', 'Conference', 'Scheduled', '2026-06-15', '2026-06-16', 'US', 'North America', 'Waldorf Astoria Monarch Beach Resort & Club', 'Dana Point', 'real-estate'],
        ['PV ModuleTech USA', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-16', '2026-06-17', 'US', 'North America', 'Napa Valley (a definir)', 'Napa', 'energia'],
        ['WHX Miami', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-17', '2026-06-19', 'US', 'North America', 'Miami Beach Convention Center', 'Miami', 'saude-medtech'],
        ['Non-QM Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-06-17', '2026-06-18', 'US', 'North America', 'Waldorf Astoria', null, 'real-estate'],
        ['FSD Chefs Immersion University of Michigan', 2026, 'C', 'Conference', 'Scheduled', '2026-06-22', '2026-06-24', 'US', 'North America', 'University of Michigan', 'Ann Arbor', 'hotelaria'],
        ['CSP Center Store Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-06-22', '2026-06-24', 'US', 'North America', 'Westin Chicago Lombard', 'Lombard', 'varejo'],
        ['Foam Expo North America', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-23', '2026-06-25', 'US', 'North America', 'Vibe Credit Union Showplace', 'Novi', 'manufatura'],
        ['Adhesives & Bonding Expo North America', 2026, 'M', 'Trade Show', 'Scheduled', '2026-06-23', '2026-06-25', 'US', 'North America', 'Vibe Credit Union Showplace', 'Novi', 'manufatura'],
        ['Short Term Rental Summer', 2026, 'C', 'Conference', 'Scheduled', '2026-06-23', '2026-06-24', 'US', 'North America', 'Omni La Costa Resort & Spa', 'Carlsbad', 'real-estate'],
        ['Real Estate Private Funds Summer', 2026, 'C', 'Conference', 'Scheduled', '2026-06-24', '2026-06-26', 'US', 'North America', 'Newport Harbor Island Resort', 'Newport', 'real-estate'],
        ['The Aesthetic Show', 2026, 'C', 'Trade Show', 'Scheduled', '2026-06-25', '2026-06-27', 'US', 'North America', 'Wynn Las Vegas', 'Las Vegas', 'estetica'],
        ['Vidcon Anaheim', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-06-25', '2026-06-27', 'US', 'North America', 'Anaheim Convention Center', 'Anaheim', 'pop-culture'],
        ['Distressed CRE West', 2026, 'C', 'Conference', 'Scheduled', '2026-07-15', '2026-07-15', 'US', 'North America', 'Waldorf Astoria Monarch Beach Resort & Club', 'Dana Point', 'real-estate'],
        ['CREATE', 2026, 'C', 'Conference', 'Scheduled', '2026-07-20', '2026-07-22', 'US', 'North America', 'Terranea Resort', 'Rancho Palos Verdes', 'hotelaria'],
        ['NBJ Summit', 2026, 'M', 'Conference', 'Scheduled', '2026-07-27', '2026-07-30', 'US', 'North America', 'Terranea Resort', 'Rancho Palos Verdes', 'ingredientes'],
        ['Black Hat USA', 2026, 'F', 'Festival', 'Scheduled', '2026-08-01', '2026-08-06', 'US', 'North America', 'Mandalay Bay Convention Center', 'Las Vegas', 'tech'],
        ['FSD Chefs Immersion West Coast', 2026, 'C', 'Conference', 'Scheduled', '2026-08-03', '2026-08-05', 'US', 'North America', 'University of California - Davis', 'Davis', 'hotelaria'],
        ['Connect Marketplace', 2026, 'C', 'Trade Show', 'Scheduled', '2026-08-24', '2026-08-26', 'US', 'North America', 'Tampa Convention Center', 'Tampa', 'hotelaria'],
        ['SFR/BTR Property Management & Operations', 2026, 'C', 'Conference', 'Scheduled', '2026-08-24', '2026-08-25', 'US', 'North America', 'Austin Marriott Downtown', 'Austin', 'real-estate'],
        ['Grocery NEXT', 2026, 'C', 'Conference', 'Scheduled', '2026-08-24', '2026-08-26', 'US', 'North America', 'Westin Chicago Lombard', 'Lombard', 'varejo'],
        ['MEDevice Boston', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-25', '2026-08-26', 'US', 'North America', 'Thomas M. Menino Convention & Exhibition Center', 'Boston', 'saude-medtech'],
        ['Middle-Market Multifamily Mountain States', 2026, 'C', 'Conference', 'Scheduled', '2026-08-26', '2026-08-27', 'US', 'North America', 'Hyatt Regency Seattle', 'Seattle', 'real-estate'],
        ['Middle-Market Multifamily Pacific Northwest', 2026, 'C', 'Conference', 'Scheduled', '2026-08-26', '2026-08-27', 'US', 'North America', 'Hyatt Regency Seattle', 'Seattle', 'real-estate'],
        ['Premiere San Antonio', 2026, 'M', 'Trade Show', 'Scheduled', '2026-08-30', '2026-08-31', 'US', 'North America', 'Henry B. Gonzalez Convention Center', 'San Antonio', 'beleza'],
        ['Solar & Storage Finance USA', 2026, 'M', 'Conference', 'Scheduled', '2026-09-15', '2026-09-16', 'US', 'North America', 'Hyatt Regency Orange County', 'Garden Grove', 'energia'],
        ['Battery Asset Management Summit USA', 2026, 'M', 'Conference', 'Scheduled', '2026-09-15', '2026-09-16', 'US', 'North America', 'Hyatt Regency Orange County', 'Garden Grove', 'energia'],
        ['Newport International Boat Show', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-17', '2026-09-20', 'US', 'North America', 'Newport Yachting Center Marina', 'Newport', 'nautico'],
        ['BHRT Fall Symposium', 2026, 'C', 'Conference', 'Scheduled', '2026-09-17', '2026-09-19', 'US', 'North America', 'JW Marriott Nashville', 'Nashville', 'estetica'],
        ['Real Estate CFO & COO East', 2026, 'C', 'Conference', 'Scheduled', '2026-09-17', '2026-09-17', 'US', 'North America', 'New York Hilton Midtown', 'Nova York', 'real-estate'],
        ['Pharmaceutical Compliance Conference West', 2026, 'C', 'Conference', 'Scheduled', '2026-09-17', '2026-09-18', 'US', 'North America', 'Hilton La Jolla Torrey Pines', 'La Jolla', 'farma'],
        ['CSP Tobacco+ Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-09-16', '2026-09-18', 'US', 'North America', 'Hilton Oak Brook Hills Resort', 'Oak Brook', 'varejo'],
        ['Build-to-Rent Fall', 2026, 'C', 'Conference', 'Scheduled', '2026-09-09', '2026-09-10', 'US', 'North America', 'Omni Dallas Hotel', 'Dallas', 'real-estate'],
        ['Finovate Fall', 2026, 'F', 'Festival', 'Scheduled', '2026-09-09', '2026-09-11', 'US', 'North America', 'New York Marriott Marquis', 'Nova York', 'tech'],
        ['Distressed Forum for Bank Special Assets Midwest', 2026, 'C', 'Conference', 'Scheduled', '2026-09-09', '2026-09-10', 'US', 'North America', 'InterContinental Chicago Magnificent Mile', 'Chicago', 'real-estate'],
        ['Manufactured Housing East', 2026, 'C', 'Conference', 'Scheduled', '2026-09-09', '2026-09-10', 'US', 'North America', 'Hilton Nashville Downtown', 'Nashville', 'real-estate'],
        ['Wealth Management Industry Awards', 2026, 'C', 'Awards', 'Scheduled', '2026-09-10', '2026-09-10', 'US', 'North America', 'Cipriani 42nd Street', 'Nova York', 'real-estate'],
        ['COTERIE / SOURCING New York September', 2026, 'M', 'Trade Show', 'Scheduled', '2026-09-09', '2026-09-11', 'US', 'North America', 'Jacob K. Javits Convention Center', 'Nova York', 'moda'],
        ['Data Center World Power', 2026, 'C', 'Trade Show', 'Scheduled', '2026-09-21', '2026-09-23', 'US', 'North America', 'Gaylord Texan Resort & Convention Center', 'Grapevine', 'tech'],
        ['Content Marketing World', 2026, 'F', 'Festival', 'Scheduled', '2026-10-05', '2026-10-07', 'US', 'North America', 'Colorado Convention Center', 'Denver', 'marketing'],
        ['Medicaid Drug Rebate Program Summit', 2026, 'C', 'Conference', 'Scheduled', '2026-10-05', '2026-10-07', 'US', 'North America', 'Fairmont Chicago Millennium Park', 'Chicago', 'farma'],
        ['Untitled Art - Houston', 2026, 'M', 'Consumer Show', 'Scheduled', '2026-10-02', '2026-10-04', 'US', 'North America', 'George R. Brown Convention Center', 'Houston', 'arte'],
        ['Middle-Market Multifamily Carolinas', 2026, 'C', 'Conference', 'Scheduled', '2026-10-13', '2026-10-14', 'US', 'North America', 'Omni Charlotte Hotel', 'Charlotte', 'real-estate'],
        ['Distressed CRE East', 2026, 'C', 'Conference', 'Scheduled', '2026-10-14', '2026-10-14', 'US', 'North America', 'Union League Club', 'Nova York', 'real-estate'],
        ['Cardiometabolic Health Congress', 2026, 'C', 'Conference', 'Scheduled', '2026-10-14', '2026-10-17', 'US', 'North America', 'Boston Park Plaza Hotel', 'Boston', 'saude-medtech'],
        ['Industrial Outdoor Storage', 2026, 'C', 'Conference', 'Scheduled', '2026-10-15', '2026-10-15', 'US', 'North America', 'Westin Long Beach', 'Long Beach', 'real-estate'],
        ['Data Centers Private Equity West', 2026, 'C', 'Conference', 'Scheduled', '2026-10-15', '2026-10-15', 'US', 'North America', 'Fremont Marriott Silicon Valley', 'Fremont', 'tech'],
        ['Nest Chapter Gathering - Atlanta', 2026, 'C', 'Other', 'Scheduled', '2026-10-15', '2026-10-15', 'US', 'North America', 'The Coca-Cola Company HQ', 'Atlanta', 'varejo'],
        ['Beauty New York', 2026, 'M', 'Consumer Show', 'Scheduled', '2026-10-16', '2026-10-18', 'US', 'North America', 'World Trade Center - Oculus', 'Nova York', 'beleza'],
        ['C-StoreTEC', 2026, 'C', 'Conference', 'Scheduled', '2026-10-26', '2026-10-28', 'US', 'North America', 'Westin Chicago Lombard', 'Lombard', 'varejo'],
        ['Retail Leader of the Year', 2026, 'C', 'Awards', 'Scheduled', '2026-10-26', '2026-10-26', 'US', 'North America', 'Las Vegas Convention Center', 'Las Vegas', 'varejo'],
        ['Real Estate Family Office & Private Wealth East', 2026, 'C', 'Conference', 'Scheduled', '2026-10-26', '2026-10-27', 'US', 'North America', 'Loews Coral Gables Hotel', 'Coral Gables', 'real-estate'],
        ['Mortgage AI', 2026, 'C', 'Conference', 'Scheduled', '2026-10-26', '2026-10-27', 'US', 'North America', 'Waldorf Astoria Monarch Beach Resort & Club', 'Dana Point', 'real-estate'],
        ['Fort Lauderdale International Boat Show', 2026, 'M', 'Consumer Show', 'Scheduled', '2026-10-28', '2026-11-01', 'US', 'North America', 'Bahia Mar Yachting Center', 'Fort Lauderdale', 'nautico'],
        ['SupplySide Global', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-28', '2026-10-30', 'US', 'North America', 'Mandalay Bay Convention Center', 'Las Vegas', 'ingredientes'],
        ['MD&M Midwest', 2026, 'M', 'Trade Show', 'Scheduled', '2026-10-28', '2026-10-29', 'US', 'North America', 'Minneapolis Convention Center', 'Minneapolis', 'saude-medtech'],
        ['Connect Texas', 2026, 'C', 'Trade Show', 'Scheduled', '2026-10-28', '2026-10-29', 'US', 'North America', 'The Woodlands Waterway Marriott Hotel', 'The Woodlands', 'real-estate'],
        ['Real Estate Asset Management', 2026, 'C', 'Conference', 'Scheduled', '2026-10-29', '2026-10-30', 'US', 'North America', 'Marriott Dallas Uptown', 'Dallas', 'real-estate'],
        ['NCN Fall Investor Meeting', 2026, 'M', 'Other', 'Scheduled', '2026-11-11', '2026-11-11', 'US', 'North America', 'Golden Gate Club', 'San Francisco', 'varejo'],
        ['PPLI / VA Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-11-19', '2026-11-19', 'US', 'North America', 'The Diplomat Beach Resort Hollywood', 'Hollywood', 'real-estate'],
        ['Residential Ground-Up Construction Fall', 2026, 'C', 'Conference', 'Scheduled', '2026-11-19', '2026-11-20', 'US', 'North America', 'JW Marriott Atlanta Buckhead', 'Atlanta', 'real-estate'],
        ['FAN EXPO San Francisco', 2026, 'C', 'Consumer Show', 'Scheduled', '2026-11-27', '2026-11-29', 'US', 'North America', 'Moscone Center', 'San Francisco', 'pop-culture'],
        ['ExCred Americas', 2026, 'C', 'Conference', 'Scheduled', '2026-11-28', '2026-11-28', 'US', 'North America', 'The Westin New York at Times Square', 'Nova York', 'real-estate'],
        ['Biostimulants World Congress', 2026, 'C', 'Conference', 'Scheduled', '2026-11-30', '2026-12-03', 'US', 'North America', 'SAFE Credit Union Convention Center', 'Sacramento', 'agro'],
        ['Art Miami', 2026, 'M', 'Consumer Show', 'Scheduled', '2026-12-01', '2026-12-06', 'US', 'North America', 'One Herald Plaza', 'Miami', 'arte'],
        ['Trade & Channel Strategies', 2026, 'C', 'Conference', 'Scheduled', '2026-12-08', '2026-12-10', 'US', 'North America', 'W Philadelphia', 'Filadelfia', 'varejo'],
        ['CSP Forecourt Forum', 2026, 'C', 'Conference', 'Scheduled', '2026-12-09', '2026-12-11', 'US', 'North America', 'Kimpton Miralina Resort', 'Paradise Valley', 'varejo'],
        ['Multifamily AI', 2026, 'C', 'Conference', 'Scheduled', '2026-12-09', '2026-12-09', 'US', 'North America', 'Fairmont Scottsdale Princess', 'Scottsdale', 'real-estate'],
        ['AI Summit New York', 2026, 'C', 'Trade Show', 'Scheduled', '2026-12-09', '2026-12-10', 'US', 'North America', 'Jacob K. Javits Convention Center', 'Nova York', 'tech'],
        ['A4M Winter World Congress', 2026, 'C', 'Conference', 'Scheduled', '2026-12-10', '2026-12-13', 'US', 'North America', 'The Venetian Expo Center', 'Las Vegas', 'estetica'],
        ['MedSpa Pro', 2026, 'C', 'Conference', 'Scheduled', '2026-12-10', '2026-12-13', 'US', 'North America', 'The Venetian Expo Center', 'Las Vegas', 'estetica'],
        ['LongevityFest', 2026, 'C', 'Conference', 'Scheduled', '2026-12-11', '2026-12-13', 'US', 'North America', 'The Venetian Expo Center', 'Las Vegas', 'estetica'],
        ['Antibody Engineering & Therapeutics', 2026, 'C', 'Conference', 'Scheduled', '2026-12-13', '2026-12-16', 'US', 'North America', 'Marriott Marquis San Diego Marina', 'San Diego', 'farma'],
        ['destination:miami', 2026, 'M', 'Trade Show', 'Scheduled', '2026-12-31', '2026-12-31', 'US', 'North America', 'A definir', 'Miami', null],
        ['Debt Service Coverage Ratio Forum', 2027, 'C', 'Conference', 'Scheduled', '2027-01-01', '2027-01-01', 'US', 'North America', 'JW Marriott Miami Turnberry Resort & Spa', 'Aventura', 'real-estate'],
        ['FAN EXPO New Orleans', 2027, 'C', 'Consumer Show', 'Scheduled', '2027-01-08', '2027-01-10', 'US', 'North America', 'Ernest N. Morial Convention Center', 'Nova Orleans', 'pop-culture'],
        ['St. Petersburg Power & Sailboat Show', 2027, 'M', 'Consumer Show', 'Scheduled', '2027-01-14', '2027-01-17', 'US', 'North America', 'Duke Energy Center for the Arts', 'St. Petersburg', 'nautico'],
    ];

    public function run(): void
    {
        $sectors = Sector::pluck('id', 'slug');

        foreach (self::EVENTS as [$name, $year, $div, $type, $status, $start, $end, $country, $region, $facility, $city, $slug]) {
            Event::updateOrCreate(
                ['name' => $name.' ('.$year.')', 'year' => $year],
                [
                    'division' => self::DIVISIONS[$div],
                    'event_type' => $type,
                    'status' => $status,
                    'start_date' => $start,
                    'end_date' => $end,
                    'country' => $country,
                    'region' => $region,
                    'facility' => $facility,
                    'city' => $city,
                    'sector_id' => $slug ? ($sectors[$slug] ?? null) : null,
                ]
            );
        }
    }
}
