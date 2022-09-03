<div class="relative flex flex-col">
    <div class="h-full w-full p-2 flex-1">
        {{ $this->form }}
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"
        integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.1/chartjs-plugin-zoom.min.js"
        integrity="sha512-klQv6lz2YR+MecyFYMFRuU2eAl8IPRo6zHnsc9n142TJuJHS8CG0ix4Oq9na9ceeg1u5EkBfZsFcV3U7J51iew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <div wire:ignore id="legend"></div>
    <canvas wire:ignore id="myChart" width="40" height="40"></canvas>
    <script wire:ignore>
        const ctx = document.getElementById('myChart').getContext('2d');
        Chart.register({
            id: 'emptyChart',
            afterDraw(chart, args, options) {
                const {
                    datasets
                } = chart.data;
                let hasData = false;

                for (let dataset of datasets) {
                    if (dataset.data.length > 0 && dataset.data.some(item => item !== 0)) {
                        hasData = true;
                        break;
                    }
                }

                if (!hasData) {
                    const {
                        chartArea: {
                            left,
                            top,
                            right,
                            bottom
                        },
                        ctx
                    } = chart;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;

                    chart.clear();
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#8c8c8c';
                    ctx.fillText('No data to display', centerX, centerY);
                    ctx.restore();
                }
            }
        });

        const getOrCreateLegendList = (chart, id) => {
            const legendContainer = document.getElementById(id);
            let listContainer = legendContainer.querySelector('ul');

            if (!listContainer) {
                listContainer = document.createElement('ul');
                listContainer.style.display = 'flex';
                listContainer.style.flexDirection = 'row';
                listContainer.style.margin = 0;
                listContainer.style.padding = 0;

                legendContainer.appendChild(listContainer);
            }

            return listContainer;
        };

        Chart.register({
            id: 'htmlLegend',
            afterUpdate(chart, args, options) {
                const ul = getOrCreateLegendList(chart, options.containerID);
                ul.style.flexWrap = 'wrap';
                ul.style.margin = '5px 10px';
                while (ul.firstChild) {
                    ul.firstChild.remove();
                }
                const items = chart.options.plugins.legend.labels.generateLabels(chart);

                items.forEach(item => {
                    const li = document.createElement('li');
                    li.style.alignItems = 'center';
                    li.style.cursor = 'pointer';
                    li.style.display = 'flex';
                    li.style.flexDirection = 'row';
                    li.style.margin = '2px';

                    li.onclick = () => {
                        const {
                            type
                        } = chart.config;
                        chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item
                            .datasetIndex));
                        chart.update();
                    };

                    const boxSpan = document.createElement('span');
                    boxSpan.style.background = item.fillStyle;
                    boxSpan.style.borderColor = item.strokeStyle;
                    boxSpan.style.borderWidth = item.lineWidth + 'px';
                    boxSpan.style.display = 'inline-block';
                    boxSpan.style.height = '10px';
                    boxSpan.style.margin = '0px 5px';
                    boxSpan.style.width = '10px';

                    const textContainer = document.createElement('p');
                    textContainer.style.margin = 0;
                    textContainer.style.padding = 0;
                    textContainer.style.textDecoration = item.hidden ? 'line-through' : '';
                    textContainer.style.fontSize = '12px';
                    textContainer.style.userSelect = 'none';
                    textContainer.style.flex = 1;

                    const text = document.createTextNode(item.text);
                    textContainer.appendChild(text);

                    li.appendChild(boxSpan);
                    li.appendChild(textContainer);
                    ul.appendChild(li);
                });
            }
        });

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {},
            options: {
                datasets: {
                    line: {
                        pointRadius: 1,
                        pointBorderWidth: 1,
                        borderWidth: 2,
                        tension: 0.3,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgb(140, 140, 140, 0.2)'
                        },
                        ticks: {
                            color: 'rgb(140, 140, 140)'
                        }
                    },
                    y: {
                        reverse: true,
                        grid: {
                            color: 'rgb(140, 140, 140, 0.2)'
                        },
                        ticks: {
                            color: 'rgb(140, 140, 140)'
                        }
                    },
                },
                plugins: {
                    zoom: {
                        pan: {
                            enabled: true,
                            mode: 'y',
                        },
                        zoom: {
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true,
                            },
                            mode: 'y',
                        },
                        limits: {
                            y: {
                                min: 0
                            },
                        },
                    },
                    legend: {
                        display: false,
                    },
                    htmlLegend: {
                        containerID: 'legend'
                    },
                },
            }
        });
        Livewire.on('chartDataUpdated', (data) => {
            console.log('Updating chart data');
            myChart.data = data;
            myChart.update();
            myChart.resetZoom();
        });
    </script>

</div>
