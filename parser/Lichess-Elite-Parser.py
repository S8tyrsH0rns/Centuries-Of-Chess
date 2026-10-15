import getpass
import psycopg2
import re

conn = psycopg2.connect(database = "chess",
user = "postgres",
host = 'localhost',
password = getpass.getpass("Postgres password: "),
port = 5432
)

cur = conn.cursor()

def parse_elite_file(file_path):
    file = open(file_path, "r")
    
    currentGame = {}
    gameMoves = ""
    
    for line in file:
        line = line.strip()
        
        if (line.startswith("[Event ") and currentGame):
            #Add moves to currentGame dictionary
            currentGame["Moves"] = gameMoves + " "
            
            #Send previous game to be converted to SQL
            convert_to_sql(currentGame)
            
            #Reset variables for next game in dataset
            currentGame = {}
            gameMoves = ""
            
        if (line.startswith("[")):
            heading = line.split('"')[0].strip("[] ")
            value = line.split('"')[1]
            
            currentGame[heading] = value
        else:
            #Save the move history of a game, removing any numbers and the victory result
            gameMoves += " " + line
            gameMoves = re.sub(r'\d+\.\s*', '', gameMoves)
            gameMoves = re.sub(r'\s*(1-0|0-1|1/2-1/2|\*)\s*$', '', gameMoves)
            gameMoves = ' '.join(gameMoves.split())
            
            
    if currentGame:
        #add last moves to currentGame dictionary
        currentGame["Moves"] = gameMoves + " "
        convert_to_sql(currentGame)
        
    file.close()

def convert_to_sql(data):
    #Convert the currentGame dictionary to SQL for input into database.
    
    match data["Result"]:
        case "1-0":
            colorWin = "w"
        case "0-1":
            colorWin = "b"
        case "1/2-1/2":
            colorWin = "d"
        case _:
            return
    
    
    sqlQuery = """
    INSERT INTO games (
    source,
    white_name,
    black_name,
    white_elo,
    black_elo,
    year,
    result,
    moves
    )
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s);
    """
    
    values = (
    "Lichess_Elite",
    data["White"],
    data["Black"],
    int(data["WhiteElo"]),
    int(data["BlackElo"]),
    int(fileName[-11:-7]),
    colorWin,
    data["Moves"]
    )
    
    cur.execute(sqlQuery, values)
    print("Added Entry!")
    
    



testName = "lichess_elite_2014-01.pgn"

#This section is used to import mutliple pgn files at once but can be used to add just one
#Simply set the year range, then the range of months you want to import
for year in range(2014,2020):
    for month in range(1,13):
        if (month < 10):
            fileName = "lichess_elite_" + str(year) + "-0" + str(month) + ".pgn"
            print("Loading", fileName)
            parse_elite_file(fileName)
            conn.commit()
        else:
            fileName = "lichess_elite_" + str(year) + "-" + str(month) + ".pgn"
            print("Loading", fileName)
            parse_elite_file(fileName)
            conn.commit()

#conn.commit()
cur.close()
conn.close()

input("Press Enter to close...")
