import React, {useEffect, useState} from 'react';
import {VictoryPie} from "victory";

const PieDiagram = ({data}) => {
    const [color, setColor] = useState('black');

    useEffect(() => {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        if(currentTheme === 'dark') {
            setColor('white');
        } else {
            setColor('black');
        }
    }, []);

    return (
        <VictoryPie
            height={350}
            data={data}
            colorScale={["#b0a98b", "#9d4f36", "#828a48", "#62833c", "#00796B", "#006064"]}
            labels={({ datum }) => `${datum.x}: ${datum.y}`}
            style={{
                labels: { fill: `${color}`, fontSize: 12 },
                data: {
                    fillOpacity: 1, stroke: "black", strokeWidth: 1
                },
            }}
        />
    );
};

export default PieDiagram;