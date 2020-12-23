<?php
    //this class will create a json file of the starwars api database
    class BuildStarWarsJson{
        protected $urls;
        protected $databaseData;
        
        public function __construct(){
            $this->initVariables();
            
            //$print_r()
        }
        
        //initializes the local variables
        private function initVariables(){
            //these are the first pages of the starwars urls
            $this->urls = [
                "person" => "https://swapi.dev/api/people/.json?page=1",
                "planets" => "https://swapi.dev/api/planets/.json?page=1",
                "starShips" => "https://swapi.dev/api/starships/.json?page=1",
                "species" => "https://swapi.dev/api/species/.json?page=1",
                "films" => "https://swapi.dev/api/films/.json?page=1",
                "vehicles" => "https://swapi.dev/api/vehicles/.json?page=1"
                ];
        
        print_r($this->urls);
        }
        
        //gets the data for the passedURL
        protected function getData($dataURL){
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

            //return the data
            return $mergedArray;
        }
        
        //checks the input for a homeworld, and sets the correct homeworld if exists
        protected function checkAndSetHomeworld($mergedArray){


            //set the correct homeworlds by using the planets
            foreach($mergedArray as $dataElement){
                if(isset($dataElement->homeworld)){
                    $regExp = "/\d+/i";
                    $homeworld; //Holds the index of the homeworld

                    preg_match_all($regExp,$dataElement->homeworld,$homeworld);
                    $homeworld = $homeworld[0][0] - 1; //Correct index of the homeworld

                    $homeworld = $this->planetData[$homeworld]->name;
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