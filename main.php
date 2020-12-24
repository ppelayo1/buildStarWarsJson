<?php
    //this class will create a json file of the starwars api database
    class BuildStarWarsJson{
        protected $urls;
        protected $databaseData;
        protected $outputFileName;
        
        public function __construct(){
            $this->initVariables();
            $this->buildDataBase();
            //$print_r()
        }
        
        //initializes the local variables
        private function initVariables(){
            //these are the first pages of the starwars urls
            $this->urls = [
                "planets" => "https://swapi.dev/api/planets/.json?page=1",
                "person" => "https://swapi.dev/api/people/.json?page=1",
                "starShips" => "https://swapi.dev/api/starships/.json?page=1",
                "species" => "https://swapi.dev/api/species/.json?page=1",
                "films" => "https://swapi.dev/api/films/.json?page=1",
                "vehicles" => "https://swapi.dev/api/vehicles/.json?page=1"
                ];
            $this->outputFileName = "starwarsData.json";
            $this->databaseData = [];
        
        
        }
        
        protected function buildDataBase(){
            //local variables
            $planets = null;   //holds the planets
            $dataSet = [];     //holds the current dataSet retireved from the getData function

            //build planet's data set
            array_push($this->databaseData,$this->getPlanets());
            $planets = $this->databaseData[0];
            
            //build person's dataSet
            array_push($this->databaseData,$this->getPerson($planets));
            
            
            print_r(json_encode($this->databaseData));
            
        }
        
        //builds and returns the planets dataSet
        protected function getPlanets(){
            //local variables
            $planets = null;   //holds the planets
            $dataSet = [];     //holds the current dataSet retireved from the getData function
            $dataSetToReturn = [];

            //add planets dataset
            $dataSet = $this->getData($this->urls['planets'],$planets);
            $planets = $dataSet;
            
            //build planets
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->rotation_period = $dataElement->rotation_period;
                $dataObject->orbital_period = $dataElement->orbital_period;
                $dataObject->diameter = $dataElement->diameter;
                $dataObject->climate = $dataElement->climate;
                $dataObject->gravity = $dataElement->gravity;
                $dataObject->terrain = $dataElement->terrain;
                $dataObject->surface_water = $dataElement->surface_water;
                $dataObject->population = $dataElement->population;
                
                array_push($dataSetToReturn, $dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //builds the person's dataset
        protected function getPerson($planets){
            //local variables
            $dataSet = [];     //holds the current dataSet 
            $dataSetToReturn = [];
            
            //add person's dataSet
            $dataSet = $this->getData($this->urls['person'],$planets);
            
            //build persons
            foreach($dataSet as $dataElement){
                $dataObject = new stdClass();
                
                $dataObject->name = $dataElement->name;
                $dataObject->height = $dataElement->height;
                $dataObject->mass = $dataElement->mass;
                $dataObject->birth_year = $dataElement->birth_year;
                $dataObject->gender = $dataElement->gender;
                $dataObject->homeworld = $dataElement->homeworld;
                
                array_push($dataSetToReturn,$dataObject);
            }
            
            return $dataSetToReturn;
        }
        
        //gets the data for the passedURL
        protected function getData($dataURL,$planets){
            //local variables
            $firstSet = json_decode(file_get_contents($dataURL));
            $secondSet;
            $mergedArray = $firstSet->results;

            if(isset($firstSet->next)){
                $secondSet = json_decode(file_get_contents($firstSet->next));
                $mergedArray = array_merge($firstSet->results,$secondSet->results);
            }

            //continue to add to the mergedArray as long as new data is found
            while(isset($secondSet->next)){
                $secondSet = json_decode(file_get_contents($secondSet->next));

                $mergedArray = array_merge($mergedArray,$secondSet->results); 
            }
            
            //check if homeworld exists and needs to be set
            $mergedArray = $this->checkAndSetHomeworld($mergedArray,$planets);

            //return the data
            return $mergedArray;
        }
        
        //checks the input for a homeworld, and sets the correct homeworld if exists
        protected function checkAndSetHomeworld($mergedArray,$planets){
            
            
            //set the correct homeworlds by using the planets
            foreach($mergedArray as $dataElement){
                if(isset($dataElement->homeworld)){
                    $regExp = "/\d+/i";
                    $homeworld; //Holds the index of the homeworld

                    preg_match_all($regExp,$dataElement->homeworld,$homeworld);
                    $homeworld = $homeworld[0][0] - 1; //Correct index of the homeworld

                    $homeworld = $planets[$homeworld]->name;
                    $dataElement->homeworld = $homeworld;
                }else{
                    $dataElement->homeworld = 'n/a';
                }
            }

            return $mergedArray;
        }
        
    }

    new BuildStarWarsJson();

    function outPutJson(){
        //these are the first pages of the starwars urls
        $urls = [
            "person" => "https://swapi.dev/api/people/.json?page=1",
            "planets" => "https://swapi.dev/api/planets/.json?page=1",
            "starShips" => "https://swapi.dev/api/starships/.json?page=1",
            "species" => "https://swapi.dev/api/species/.json?page=1",
            "films" => "https://swapi.dev/api/films/.json?page=1",
            "vehicles" => "https://swapi.dev/api/vehicles/.json?page=1"
            ];
        
        //holds all the star wars data
        
        
        print_r($urls);
    }

    //outPutJson();

    //gets the data for the passedURL
    function getData($dataURL){
        //local variables
        $firstSet = json_decode(file_get_contents($dataURL));
        $secondSet;
        $mergedArray = $firstSet->results;
        
        if(isset($firstSet->next)){
            $secondSet = json_decode(file_get_contents($firstSet->next));
            $mergedArray = array_merge($firstSet->results,$secondSet->results);
        }
        
        //continue to add to the mergedArray as long as new data is found
        while(isset($secondSet->next)){
            $secondSet = json_decode(file_get_contents($secondSet->next));
            
            $mergedArray = array_merge($mergedArray,$secondSet->results); 
        }
        
        //check if homeworld exists and needs to be set
        $mergedArray = $this->checkAndSetHomeworld($mergedArray);
        
        //sanitze the data
        $mergedArray = $this->sanitizeSQL($mergedArray);
        
        //return the data
        return $mergedArray;
    }








    /*$file = 'starwarsData.json';

    $personsURL = "https://swapi.dev/api/people/.json?page=1";
    $data = file_get_contents($personsURL);
    
    file_put_contents($file,$data);

    echo $data;*/
?>