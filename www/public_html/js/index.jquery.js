class Cargomation{
    library = [];

    constructor(){
        var self =this;
        self.library.data = "test";
    }

    notification(){
       
    }

    process(tab){
        
    }

    getDateFormatString(locale,extension='') {
    
        const options = {
          day: "numeric",
          month: "numeric",
          year: "numeric",
        };

        if(extension =='hasTime'){
            options = {
          hour: "numeric",
          minute: "numeric",
          second: "numeric",
          day: "numeric",
          month: "numeric",
          year: "numeric",
        };
        }
      
        const formatObj = new Intl.DateTimeFormat(locale, options).formatToParts(
          Date.now()
        );
        
        return formatObj
          .map((obj) => {
            switch (obj.type) {
              case "hour":
                return "HH";
              case "minute":
                return "MM";
              case "second":
                return "SS";
              case "day":
                return "DD";
              case "month":
                return "MM";
              case "year":
                return "YYYY";
              default:
                return obj.value;
            }
          })
          .join("");
    }
}