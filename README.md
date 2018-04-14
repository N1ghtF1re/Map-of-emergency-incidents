<h1 align="center">BrakhMen Emergency Map</h1>
<p align="center"><img src="http://brakhmen.info/img/gitlogo.png"></p>

<p align="center">
<a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents/stargazers"><img src="https://img.shields.io/github/stars/N1ghtF1re/Map-of-emergency-incidents.svg" alt="Stars"></a>
<a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents/releases"><img src="https://img.shields.io/github/downloads/N1ghtF1re/Map-of-emergency-incidents/latest/total.svg" alt="Total Downloads"></a>
<a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents/releases"><img src="https://img.shields.io/github/tag/N1ghtF1re/Map-of-emergency-incidents.svg" alt="Latest Stable Version"></a>
<a href="https://github.com/N1ghtF1re/Map-of-emergency-incidents/blob/master/LICENSE"><img src="https://img.shields.io/github/license/N1ghtF1re/Map-of-emergency-incidents.svg" alt="License"></a>
</p>
 
 ## About the program

Developers of Emergency Map were tasked to implement a combined visualization of the data on the example Ministry’s of Emergency Situations of fire accidents statistics.
The product should easily adapt to any sort of data and be a universal tool for statistical purposes. 

At the moment, there are services that solve individual tasks of visualizing data. (Example: the criminal map of Minsk, a map of cellular coverage, etc.) However, there is no available ready-made solution that each developer could use to visualize the necessary data in a few clicks. 

Developed solution allows to visualize multidimensional information effectively.It has an user-friendly interface. Code is easy to modify for any sphere of usage. Application of color mixing enhances perception and analyzation of information.

## Used technologies
**Languages:**  PHP(server part), JS(client part),HTML+CSS(markup + style), SQL(Database requests).<br>
**DBMS:** MySQL<br>
**Third-party APIs:** Yandex map API (map rendering, polygon coloring, polygon click actions), Nominatim API(obtaining requested geographical region polygon).<br>
**Use example:** site brakh.men/map<br>

## How to use

![Structure of data table](https://github.com/N1ghtF1re/Map-of-emergency-incidents/blob/master/docs/db-table-structure.PNG?raw=true)

SQL request to create table:
``` sql
CREATE TABLE `TableName` (
  `id` INT(100) NOT NULL,
  `Region` VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
  `Date` DATE NOT NULL,
  `Situation` INT(20) NOT NULL,
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
ALTER TABLE `TableName`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `TableName`
  MODIFY `id` INT(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
```

Database completion guide for developers:<br>
Region - the geographical name of the object to be painted (as precisely as possible). Example: Brooklyn, New York City, New York, USA, Earth, Sol, Milky Way, Universe (Unsign)<br>
Date - date, tied to the situation.<br>
Situation - situation number. (Each situation is assigned a unique number)<br>
Yaer - year of origin of the situation (For convenience of sorting by years)<br>
<br><br>
**Config.php** file have to be filled

## Developers

In the development of this chic game involved:
+ [**Pankratiew Alexandr**](https://vk.com/sasha_pankratiew)
+ [**Holubeu Kiryl**](https://vk.com/smertowing)
+ [**Pilinko Nikita**](https://vk.com/mineralsfree)
