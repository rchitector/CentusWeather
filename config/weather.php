<?php

return [
    'number_of_visible_next_hours' => 2,


    'snow_ranges' => [
        [
            'min' => 0,
            'max' => 1,
            'description' => 'Trace of snow - barely noticeable, no impact on travel. Dress lightly but carry a jacket.',
            'comment' => 'Следы снега - практически незаметно, не влияет на передвижение. Одевайтесь легко, но берите куртку.'
        ],
        [
            'min' => 2,
            'max' => 3,
            'description' => 'Very light snow - minor accumulation, roads may be slightly slippery. Light winter gear recommended.',
            'comment' => 'Очень легкий снег - небольшое накопление, дороги могут быть немного скользкими. Рекомендуется легкая зимняя одежда.'
        ],
        [
            'min' => 4,
            'max' => 5,
            'description' => 'Light snow - expect some snow on roads, could slow down travel. Dress in layers, wear boots.',
            'comment' => 'Легкий снег - ожидайте снега на дорогах, возможны замедления в движении. Одевайтесь послойно, носите обувь на подошве.'
        ],
        [
            'min' => 6,
            'max' => 7,
            'description' => 'Moderate snow - roads will be slippery, visibility might be reduced. Full winter gear, including gloves and hats.',
            'comment' => 'Умеренный снег - дороги будут скользкими, видимость может ухудшиться. Полная зимняя экипировка, включая перчатки и шапку.'
        ],
        [
            'min' => 8,
            'max' => 9,
            'description' => 'Moderate to heavy snow - significant snow on roads, travel could be hazardous. Prepare for cold, wear insulated clothing.',
            'comment' => 'От умеренного к сильному снегу - значительное количество снега на дорогах, движение может быть опасным. Готовьтесь к холоду, носите утепленную одежду.'
        ],
        [
            'min' => 10,
            'max' => 12,
            'description' => 'Heavy snow - roads will likely be closed or very dangerous. Dress in heavy winter gear, avoid travel if possible.',
            'comment' => 'Сильный снег - дороги, вероятно, будут закрыты или очень опасны. Одевайтесь в тяжелую зимнюю одежду, избегайте поездок, если возможно.'
        ],
        [
            'min' => 13,
            'max' => 15,
            'description' => 'Very heavy snow - severe travel disruption, high risk of being stranded. Full winter survival gear needed.',
            'comment' => 'Очень сильный снег - серьезные нарушения в движении, высокий риск остаться в снежном плену. Требуется полное зимнее снаряжение для выживания.'
        ],
        [
            'min' => 16,
            'max' => 20,
            'description' => 'Blizzard conditions - whiteouts, roads impassable. Dress for extreme cold, carry emergency supplies.',
            'comment' => 'Метельные условия - полное отсутствие видимости, дороги непроходимы. Одевайтесь для экстремального холода, возьмите аварийные запасы.'
        ],
        [
            'min' => 21,
            'max' => 30,
            'description' => 'Severe blizzard - life-threatening conditions, stay indoors if at all possible. Extreme winter attire required.',
            'comment' => 'Сильная метель - угрожающие жизни условия, если возможно, оставайтесь дома. Требуется экстремальная зимняя одежда.'
        ],
        [
            'min' => 31,
            'max' => 50,
            'description' => 'Extreme blizzard - catastrophic conditions, emergency services may be overwhelmed. Complete winter survival kit essential.',
            'comment' => 'Экстремальная метель - катастрофические условия, службы спасения могут быть перегружены. Необходим полный комплект для выживания в зимних условиях.'
        ],
        [
            'min' => 51,
            'max' => 100,
            'description' => 'Unprecedented snowstorm - expect complete shutdown of travel. Extreme survival measures needed, stay sheltered.',
            'comment' => 'Беспрецедентный снежный шторм - ожидайте полного прекращения движения. Требуются экстремальные меры выживания, оставайтесь в укрытии.'
        ],
    ],

    'rain_ranges' => [
        [
            'min' => 0,
            'max' => 1,
            'description' => 'No rain - clear skies, normal travel conditions. Dress as per weather.',
            'comment' => 'Нет дождя - ясное небо, нормальные условия для передвижения. Одевайтесь по погоде.'
        ],
        [
            'min' => 2,
            'max' => 3,
            'description' => 'Light mist - barely noticeable, no impact on travel. Carry a light jacket or umbrella.',
            'comment' => 'Легкий туман - практически незаметен, не влияет на движение. Возьмите легкую куртку или зонт.'
        ],
        [
            'min' => 4,
            'max' => 5,
            'description' => 'Light drizzle - slight dampness, sidewalks might be wet. Umbrella or raincoat recommended.',
            'comment' => 'Легкая морось - небольшая влажность, тротуары могут быть мокрыми. Рекомендуется зонт или плащ.'
        ],
        [
            'min' => 6,
            'max' => 8,
            'description' => 'Light rain - expect wet roads, minor delays. Dress with waterproof clothing, carry an umbrella.',
            'comment' => 'Легкий дождь - ожидайте мокрые дороги, незначительные задержки. Одевайтесь в водонепроницаемую одежду, возьмите зонт.'
        ],
        [
            'min' => 9,
            'max' => 11,
            'description' => 'Moderate rain - roads may be slippery, plan extra travel time. Wear rain gear, avoid low-lying areas.',
            'comment' => 'Умеренный дождь - дороги могут быть скользкими, планируйте дополнительное время на дорогу. Носите дождевую экипировку, избегайте низменных мест.'
        ],
        [
            'min' => 12,
            'max' => 15,
            'description' => 'Heavy rain - significant water on roads, possible flooding. Full waterproof attire, be cautious of water on roads.',
            'comment' => 'Сильный дождь - много воды на дорогах, возможно затопление. Полная водонепроницаемая одежда, осторожность на дорогах.'
        ],
        [
            'min' => 16,
            'max' => 20,
            'description' => 'Very heavy rain - expect road closures, slippery conditions. Waterproof clothing, boots, prepare for delays.',
            'comment' => 'Очень сильный дождь - ожидайте закрытия дорог, скользкие условия. Водонепроницаемая одежда, сапоги, готовьтесь к задержкам.'
        ],
        [
            'min' => 21,
            'max' => 30,
            'description' => 'Torrential downpour - driving hazardous, risk of flash floods. Stay indoors if possible, full rain gear if outside.',
            'comment' => 'Ливень - вождение опасно, риск внезапных наводнений. Если возможно, оставайтесь дома, полное дождевое снаряжение если на улице.'
        ],
        [
            'min' => 31,
            'max' => 50,
            'description' => 'Heavy torrential rain - severe road conditions, widespread flooding. Avoid travel, emergency preparedness necessary.',
            'comment' => 'Сильный ливень - серьезные дорожные условия, широкое затопление. Избегайте поездок, необходима готовность к чрезвычайным ситуациям.'
        ],
        [
            'min' => 51,
            'max' => 75,
            'description' => 'Extreme rainfall - major disruptions, risk of severe flooding. Stay safe, do not travel unless absolutely necessary.',
            'comment' => 'Экстремальные осадки - серьезные нарушения, риск сильного наводнения. Останьтесь в безопасности, не путешествуйте, если это не абсолютно необходимо.'
        ],
        [
            'min' => 76,
            'max' => 100,
            'description' => 'Unprecedented flooding rain - catastrophic conditions, emergency services overwhelmed. Shelter in place, extreme caution.',
            'comment' => 'Беспрецедентный дождь, вызывающий наводнения - катастрофические условия, службы спасения перегружены. Останьтесь в укрытии, крайняя осторожность.'
        ],
    ],

    'uvi_ranges' => [
        [
            'min' => 0,
            'max' => 1,
            'description' => 'No UV exposure - no risk of sunburn. Normal outdoor activities safe without precautions.',
            'comment' => 'Нет УФ излучения - нет риска солнечных ожогов. Обычные уличные занятия безопасны без предосторожностей.'
        ],
        [
            'min' => 2,
            'max' => 2,
            'description' => 'Low exposure - minimal sunburn risk. Sunglasses optional, light clothing advisable.',
            'comment' => 'Низкая интенсивность - минимальный риск ожогов. Солнцезащитные очки по желанию, легкая одежда рекомендуется.'
        ],
        [
            'min' => 3,
            'max' => 3,
            'description' => 'Moderate exposure - moderate sunburn risk. Use sunscreen, wear a hat.',
            'comment' => 'Умеренная интенсивность - умеренный риск ожогов. Используйте солнцезащитный крем, носите шляпу.'
        ],
        [
            'min' => 4,
            'max' => 4,
            'description' => 'Moderate exposure, caution advised - risk of sunburn increases. Sunscreen, hat, and protective clothing recommended.',
            'comment' => 'Умеренная интенсивность, осторожность рекомендуется - увеличивается риск ожогов. Рекомендован солнцезащитный крем, шляпа, защитная одежда.'
        ],
        [
            'min' => 5,
            'max' => 5,
            'description' => 'High exposure - high sunburn risk. Use high SPF sunscreen, seek shade during midday.',
            'comment' => 'Высокая интенсивность - высокий риск ожогов. Используйте солнцезащитный крем с высоким SPF, ищите тень в полдень.'
        ],
        [
            'min' => 6,
            'max' => 6,
            'description' => 'Very high exposure, protection required - rapid sunburn possible. Wear long sleeves, apply sunscreen frequently.',
            'comment' => 'Очень высокая интенсивность, требуется защита - возможно быстрое обгорание. Носите одежду с длинными рукавами, часто наносите солнцезащитный крем.'
        ],
        [
            'min' => 7,
            'max' => 7,
            'description' => 'Very high exposure, increased risk - sunburn likely in minutes. Limit exposure, use UV protective gear.',
            'comment' => 'Очень высокая интенсивность, повышенный риск - ожоги вероятны за минуты. Ограничьте пребывание на солнце, используйте UV-защитное снаряжение.'
        ],
        [
            'min' => 8,
            'max' => 8,
            'description' => 'Very high exposure, extreme caution - severe sunburn if unprotected. Avoid midday sun, stay shaded.',
            'comment' => 'Очень высокая интенсивность, крайняя осторожность - серьезные ожоги при отсутствии защиты. Избегайте полуденного солнца, оставайтесь в тени.'
        ],
        [
            'min' => 9,
            'max' => 10,
            'description' => 'Extreme exposure, avoid midday sun - risk of quick, severe burns. Protective clothing essential, limit outdoor time.',
            'comment' => 'Экстремальная интенсивность, избегайте полуденного солнца - риск быстрых, серьезных ожогов. Защитная одежда обязательна, ограничьте время на улице.'
        ],
        [
            'min' => 11,
            'max' => 13,
            'description' => 'Dangerously high UV - extreme burn risk, potential for skin damage. Stay indoors or in heavy shade, full UV protection needed.',
            'comment' => 'Опасно высокий УФ индекс - экстремальный риск ожогов, возможен вред для кожи. Оставайтесь в помещении или в глубокой тени, требуется полная UV-защита.'
        ],
        [
            'min' => 14,
            'max' => 15,
            'description' => 'Extremely hazardous UV levels - severe health risk, especially for fair skin. Avoid all outdoor activities, seek immediate shade.',
            'comment' => 'Крайне опасные уровни УФ - серьезный риск для здоровья, особенно для светлой кожи. Избегайте всех уличных занятий, ищите немедленное укрытие в тени.'
        ],
    ],
];
