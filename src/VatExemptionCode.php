<?php

namespace NumNum\UBL;

/**
 * All possible Vat Exemption Codes that can be used
 * To extend, see also:
 *     https://docs.peppol.eu/poacc/billing/3.0/codelist/vatex/
 */
class VatExemptionCode
{
    /*
     * Exempt based on article 79, point c of Council Directive 2006/112/EC
     * Exemptions relating to repayment of expenditures. Remark, Repayment of expenditure is not an exemption in the sense of the VAT Directive but may be handled as such in the context of the EN16931.
     */
    public const VATEX_EU_79_C = 'VATEX-EU-79-C';

    /*
     * Exempt based on article 132 of Council Directive 2006/112/EC
     * Exemptions for certain activities in public interest.
     */
    public const VATEX_EU_132 = 'VATEX-EU-132';

    /*
     * Exempt based on article 132, section 1 (a) of Council Directive 2006/112/EC
     * The supply by the public postal services of services other than passenger transport and telecommunications services, and the supply of goods incidental thereto.
     */
    public const VATEX_EU_132_1A = 'VATEX-EU-132-1A';

    /*
     * Exempt based on article 132, section 1 (b) of Council Directive 2006/112/EC
     * Hospital and medical care and closely related activities undertaken by bodies governed by public law or, under social conditions comparable with those applicable to bodies governed by public law, by hospitals, centres for medical treatment or diagnosis and other duly recognised establishments of a similar nature
     */
    public const VATEX_EU_132_1B = 'VATEX-EU-132-1B';

    /*
     * Exempt based on article 132, section 1 (c) of Council Directive 2006/112/EC
     * The provision of medical care in the exercise of the medical and paramedical professions as defined by the Member State concerned.
     */
    public const VATEX_EU_132_1C = 'VATEX-EU-132-1C';

    /*
     * Exempt based on article 132, section 1 (d) of Council Directive 2006/112/EC
     * The supply of human organs, blood and milk.
     */
    public const VATEX_EU_132_1D = 'VATEX-EU-132-1D';

    /*
     * Exempt based on article 132, section 1 (e) of Council Directive 2006/112/EC
     * The supply of services by dental technicians in their professional capacity and the supply of dental prostheses by dentists and dental technicians.
     */
    public const VATEX_EU_132_1E = 'VATEX-EU-132-1E';

    /*
     * Exempt based on article 132, section 1 (f) of Council Directive 2006/112/EC
     * The supply of services by independent groups of persons, who are carrying on an activity which is exempt from VAT or in relation to which they are not taxable persons, for the purpose of rendering their members the services directly necessary for the exercise of that activity, where those groups merely claim from their members exact reimbursement of their share of the joint expenses, provided that such exemption is not likely to cause distortion of competition.
     */
    public const VATEX_EU_132_1F = 'VATEX-EU-132-1F';

    /*
     * Exempt based on article 132, section 1 (g) of Council Directive 2006/112/EC
     * The supply of services and of goods closely linked to welfare and social security work, including those supplied by old people's homes, by bodies governed by public law or by other bodies recognised by the Member State concerned as being devoted to social wellbeing.
     */
    public const VATEX_EU_132_1G = 'VATEX-EU-132-1G';

    /*
     * Exempt based on article 132, section 1 (h) of Council Directive 2006/112/EC
     * "The supply of services and of goods closely linked to the protection of children and young persons by bodies governed by public law or by other organisations recognised by the Member State concerned as being devoted to social wellbeing"
     */
    public const VATEX_EU_132_1H = 'VATEX-EU-132-1H';

    /*
     * Exempt based on article 132, section 1 (i) of Council Directive 2006/112/EC
     * " The provision of children's or young people's education, school or university education, vocational training or retraining, including the supply of services and of goods closely related thereto, by bodies governed by public law having such as their aim or by other organisations recognised by the Member State concerned as having similar objects."
     */
    public const VATEX_EU_132_1I = 'VATEX-EU-132-1I';

    /*
     * Exempt based on article 132, section 1 (j) of Council Directive 2006/112/EC
     * Tuition given privately by teachers and covering school or university education.
     */
    public const VATEX_EU_132_1J = 'VATEX-EU-132-1J';

    /*
     * Exempt based on article 132, section 1 (k) of Council Directive 2006/112/EC
     * The supply of staff by religious or philosophical institutions for the purpose of the activities referred to in points (b), (g), (h) and (i) and with a view to spiritual welfare.
     */
    public const VATEX_EU_132_1K = 'VATEX-EU-132-1K';

    /*
     * Exempt based on article 132, section 1 (l) of Council Directive 2006/112/EC
     * The supply of services, and the supply of goods closely linked thereto, to their members in their common interest in return for a subscription fixed in accordance with their rules by non-profitmaking organisations with aims of a political, trade-union, religious, patriotic, philosophical, philanthropic or civic nature, provided that this exemption is not likely to cause distortion of competition.
     */
    public const VATEX_EU_132_1L = 'VATEX-EU-132-1L';

    /*
     * Exempt based on article 132, section 1 (m) of Council Directive 2006/112/EC
     * The supply of certain services closely linked to sport or physical education by non-profit-making organisations to persons taking part in sport or physical education.
     */
    public const VATEX_EU_132_1M = 'VATEX-EU-132-1M';

    /*
     * Exempt based on article 132, section 1 (n) of Council Directive 2006/112/EC
     * The supply of certain cultural services, and the supply of goods closely linked thereto, by bodies governed by public law or by other cultural bodies recognised by the Member State concerned.
     */
    public const VATEX_EU_132_1N = 'VATEX-EU-132-1N';

    /*
     * Exempt based on article 132, section 1 (o) of Council Directive 2006/112/EC
     * "The supply of services and goods, by organisations whose activities are exempt pursuant to points (b), (g), (h), (i), (l), (m) and (n), in connection with fund-raising events organised exclusively for their own benefit, provided that exemption is not likely to cause distortion of competition."
     */
    public const VATEX_EU_132_1O = 'VATEX-EU-132-1O';

    /*
     * Exempt based on article 132, section 1 (p) of Council Directive 2006/112/EC
     * The supply of transport services for sick or injured persons in vehicles specially designed for the purpose, by duly authorised bodies.
     */
    public const VATEX_EU_132_1P = 'VATEX-EU-132-1P';

    /*
     * Exempt based on article 132, section 1 (q) of Council Directive 2006/112/EC
     * The activities, other than those of a commercial nature, carried out by public radio and television bodies.
     */
    public const VATEX_EU_132_1Q = 'VATEX-EU-132-1Q';

    /*
     * Exempt based on article 143 of Council Directive 2006/112/EC
     * Exemptions on importation.
     */
    public const VATEX_EU_143 = 'VATEX-EU-143';

    /*
     * Exempt based on article 143, section 1 (a) of Council Directive 2006/112/EC
     * The final importation of goods of which the supply by a taxable person would in all circumstances be exempt within their respective territory.
     */
    public const VATEX_EU_143_1A = 'VATEX-EU-143-1A';

    /*
     * Exempt based on article 143, section 1 (b) of Council Directive 2006/112/EC
     * The final importation of goods governed by Council Directives 69/169/EEC (1), 83/181/EEC (2) and 2006/79/EC (3).
     */
    public const VATEX_EU_143_1B = 'VATEX-EU-143-1B';

    /*
     * Exempt based on article 143, section 1 (c) of Council Directive 2006/112/EC
     * The final importation of goods, in free circulation from a third territory forming part of the Community customs territory, which would be entitled to exemption under point (b) if they had been imported within the meaning of the first paragraph of Article 30
     */
    public const VATEX_EU_143_1C = 'VATEX-EU-143-1C';

    /*
     * Exempt based on article 143, section 1 (d) of Council Directive 2006/112/EC
     * The importation of goods dispatched or transported from a third territory or a third country into a Member State other than that in which the dispatch or transport of the goods ends, where the supply of such goods by the importer designated or recognised under Article 201 as liable for payment of VAT is exempt under Article 138.
     */
    public const VATEX_EU_143_1D = 'VATEX-EU-143-1D';

    /*
     * Exempt based on article 143, section 1 (e) of Council Directive 2006/112/EC
     * The reimportation, by the person who exported them, of goods in the state in which they were exported, where those goods are exempt from customs duties.
     */
    public const VATEX_EU_143_1E = 'VATEX-EU-143-1E';

    /*
     * Exempt based on article 143, section 1 (f) of Council Directive 2006/112/EC
     * The importation, under diplomatic and consular arrangements, of goods which are exempt from customs duties.
     */
    public const VATEX_EU_143_1F = 'VATEX-EU-143-1F';

    /*
     * Exempt based on article 143, section 1 (fa) of Council Directive 2006/112/EC
     * "The importation of goods by the European Community, the European Atomic Energy Community, the European Central Bank or the European Investment Bank, or by the bodies set up by the Communities to which the Protocol of 8 April 1965 on the privileges and immunities of the European Communities applies, within the limits and under the conditions of that Protocol and the agreements for its implementation or the headquarters agreements, in so far as it does not lead to distortion of competition"
     */
    public const VATEX_EU_143_1FA = 'VATEX-EU-143-1FA';

    /*
     * Exempt based on article 143, section 1 (g) of Council Directive 2006/112/EC
     * " The importation of goods by international bodies, other than those referred to in point (fa), recognised as such by the public authorities of the host Member State, or by members of such bodies, within the limits and under the conditions laid down by the international conventions establishing the bodies or by headquarters agreements"
     */
    public const VATEX_EU_143_1G = 'VATEX-EU-143-1G';

    /*
     * Exempt based on article 143, section 1 (h) of Council Directive 2006/112/EC
     * The importation of goods, into Member States party to the North Atlantic Treaty, by the armed forces of other States party to that Treaty for the use of those forces or the civilian staff accompanying them or for supplying their messes or canteens where such forces take part in the common defence effort.
     */
    public const VATEX_EU_143_1H = 'VATEX-EU-143-1H';

    /*
     * Exempt based on article 143, section 1 (i) of Council Directive 2006/112/EC
     * The importation of goods by the armed forces of the United Kingdom stationed in the island of Cyprus pursuant to the Treaty of Establishment concerning the Republic of Cyprus, dated 16 August 1960, which are for the use of those forces or the civilian staff accompanying them or for supplying their messes or canteens.
     */
    public const VATEX_EU_143_1I = 'VATEX-EU-143-1I';

    /*
     * Exempt based on article 143, section 1 (j) of Council Directive 2006/112/EC
     * The importation into ports, by sea fishing undertakings, of their catches, unprocessed or after undergoing preservation for marketing but before being supplied.
     */
    public const VATEX_EU_143_1J = 'VATEX-EU-143-1J';

    /*
     * Exempt based on article 143, section 1 (k) of Council Directive 2006/112/EC
     * The importation of gold by central banks.
     */
    public const VATEX_EU_143_1K = 'VATEX-EU-143-1K';

    /*
     * Exempt based on article 143, section 1 (l) of Council Directive 2006/112/EC
     * The importation of gas through a natural gas system or any network connected to such a system or fed in from a vessel transporting gas into a natural gas system or any upstream pipeline network, of electricity or of heat or cooling energy through heating or cooling networks.
     */
    public const VATEX_EU_143_1L = 'VATEX-EU-143-1L';

    /*
     * Exempt based on article 148 of Council Directive 2006/112/EC
     * Exemptions related to international transport.
     */
    public const VATEX_EU_148 = 'VATEX-EU-148';

    /*
     * Exempt based on article 148, section (a) of Council Directive 2006/112/EC
     * Fuel supplies for commercial international transport vessels
     */
    public const VATEX_EU_148_A = 'VATEX-EU-148-A';

    /*
     * Exempt based on article 148, section (b) of Council Directive 2006/112/EC
     * Fuel supplies for fighting ships in international transport.
     */
    public const VATEX_EU_148_B = 'VATEX-EU-148-B';

    /*
     * Exempt based on article 148, section (c) of Council Directive 2006/112/EC
     * Maintenance, modification, chartering and hiring of international transport vessels.
     */
    public const VATEX_EU_148_C = 'VATEX-EU-148-C';

    /*
     * Exempt based on article 148, section (d) of Council Directive 2006/112/EC
     * Supply to of other services to commercial international transport vessels.
     */
    public const VATEX_EU_148_D = 'VATEX-EU-148-D';

    /*
     * Exempt based on article 148, section (e) of Council Directive 2006/112/EC
     * Fuel supplies for aircraft on international routes.
     */
    public const VATEX_EU_148_E = 'VATEX-EU-148-E';

    /*
     * Exempt based on article 148, section (f) of Council Directive 2006/112/EC
     * Maintenance, modification, chartering and hiring of aircraft on international routes.
     */
    public const VATEX_EU_148_F = 'VATEX-EU-148-F';

    /*
     * Exempt based on article 148, section (g) of Council Directive 2006/112/EC
     * Supply to of other services to aircraft on international routes.
     */
    public const VATEX_EU_148_G = 'VATEX-EU-148-G';

    /*
     * Exempt based on article 151 of Council Directive 2006/112/EC
     * Exemptions relating to certain Transactions treated as exports.
     */
    public const VATEX_EU_151 = 'VATEX-EU-151';

    /*
     * Exempt based on article 151, section 1 (a) of Council Directive 2006/112/EC
     * The supply of goods or services under diplomatic and consular arrangements.
     */
    public const VATEX_EU_151_1A = 'VATEX-EU-151-1A';

    /*
     * Exempt based on article 151, section 1 (aa) of Council Directive 2006/112/EC
     * The supply of goods or services to the European Community, the European Atomic Energy Community, the European Central Bank or the European Investment Bank, or to the bodies set up by the Communities to which the Protocol of 8 April 1965 on the privileges and immunities of the European Communities applies, within the limits and under the conditions of that Protocol and the agreements for its implementation or the headquarters agreements, in so far as it does not lead to distortion of competition.
     */
    public const VATEX_EU_151_1AA = 'VATEX-EU-151-1AA';

    /*
     * Exempt based on article 151, section 1 (b) of Council Directive 2006/112/EC
     * The supply of goods or services to international bodies, other than those referred to in point (aa), recognised as such by the public authorities of the host Member States, and to members of such bodies, within the limits and under the conditions laid down by the international conventions establishing the bodies or by headquarters agreements.
     */
    public const VATEX_EU_151_1B = 'VATEX-EU-151-1B';

    /*
     * Exempt based on article 151, section 1 (c) of Council Directive 2006/112/EC
     * The supply of goods or services within a Member State which is a party to the North Atlantic Treaty, intended either for the armed forces of other States party to that Treaty for the use of those forces, or of the civilian staff accompanying them, or for supplying their messes or canteens when such forces take part in the common defence effort.
     */
    public const VATEX_EU_151_1C = 'VATEX-EU-151-1C';

    /*
     * Exempt based on article 151, section 1 (d) of Council Directive 2006/112/EC
     * The supply of goods or services to another Member State, intended for the armed forces of any State which is a party to the North Atlantic Treaty, other than the Member State of destination itself, for the use of those forces, or of the civilian staff accompanying them, or for supplying their messes or canteens when such forces take part in the common defence effort.
     */
    public const VATEX_EU_151_1D = 'VATEX-EU-151-1D';

    /*
     * Exempt based on article 151, section 1 (e) of Council Directive 2006/112/EC
     * The supply of goods or services to the armed forces of the United Kingdom stationed in the island of Cyprus pursuant to the Treaty of Establishment concerning the Republic of Cyprus, dated 16 August 1960, which are for the use of those forces, or of the civilian staff accompanying them, or for supplying their messes or canteens.
     */
    public const VATEX_EU_151_1E = 'VATEX-EU-151-1E';

    /*
     * Exempt based on article 309 of Council Directive 2006/112/EC
     * Travel agents performed outside of EU.
     */
    public const VATEX_EU_309 = 'VATEX-EU-309';

    /*
     * Reverse charge
     * Supports EN 16931-1 rule BR-AE-10 - Only use with VAT category code AE
     */
    public const VATEX_EU_AE = 'VATEX-EU-AE';

    /*
     * Intra-Community acquisition from second hand means of transport
     * Second-hand means of transport - Indication that VAT has been paid according to the relevant transitional arrangements - Only use with VAT category code E
     */
    public const VATEX_EU_D = 'VATEX-EU-D';

    /*
     * Intra-Community acquisition of second hand goods
     * Second-hand goods - Indication that the VAT margin scheme for second-hand goods has been applied. - Only use with VAT category code E
     */
    public const VATEX_EU_F = 'VATEX-EU-F';

    /*
     * Export outside the EU
     * Supports EN 16931-1 rule BR-G-10 - Only use with VAT category code G
     */
    public const VATEX_EU_G = 'VATEX-EU-G';

    /*
     * Intra-Community acquisition of works of art
     * Works of art - Indication that the VAT margin scheme for works of art has been applied. - Only use with VAT category code E
     */
    public const VATEX_EU_I = 'VATEX-EU-I';

    /*
     * Intra-Community supply
     * Supports EN 16931-1 rule BR-IC-10 - Only use with VAT category code K
     */
    public const VATEX_EU_IC = 'VATEX-EU-IC';

    /*
     * Not subject to VAT
     * Supports EN 16931-1 rule BR-O-10 - Only use with VAT category code O
     */
    public const VATEX_EU_O = 'VATEX-EU-O';

    /*
     * Intra-Community acquisition of collectors items and antiques
     * Collectors' items and antiques - Indication that the VAT margin scheme for collector’s items and antiques has been applied. - Only use with VAT category code E
     */
    public const VATEX_EU_J = 'VATEX-EU-J';
}
