# Chess test
Simple chess playing library

## How to use

#### UI Way

- Having docker-compose installed: https://docs.docker.com/compose/
```
git clone https://github.com/Makiavelo/chess-test.git
cd chess-test
docker-compose up --build
```

Open your browser and go to: 
```
http://localhost:8001
```

Drag the desired pieces into the board, click 'start' and the pieces will start moving and a report will show up. Also the moves will be added in real-time.

click on the 'Clear' button to re-start.

### CLI Way

- Having PHP7 installed with cli access
```
git clone https://github.com/Makiavelo/chess-test.git
cd chess-test
php test.php
```

It will display the report and movements log. Check test.php to customize the initial pieces.


### Final notes

The UI is not bullet proof, and doesn't validate the amount of pieces. I just implemented a simple JS library to have a nice visual board and pieces.
On the CLI side, I just dump the reports via print_r.
