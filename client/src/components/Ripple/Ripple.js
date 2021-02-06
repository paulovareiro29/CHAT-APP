export const Ripple = ({color = "#FFFFFF",duration = 1000, children}) => {

  

    function createRipple(e) {
        /*  
            Se clicou no div do ripple, ou seja , não no component child, ignora. 
            Isto acontece pois a borda da child pode ser redonda
        */
        if(e.target.className === "ripple-effect"){ 
            return
        }


        const bb = e.target.getBoundingClientRect()

        var position = {
            x: e.clientX - bb.left,
            y: e.clientY - bb.top,
        }

        let rippleElement = document.createElement("span");
        
        rippleElement.className = "ripple";

        rippleElement.style.left = position.x + "px";
        rippleElement.style.top = position.y + "px";

        rippleElement.style.background = color;
        rippleElement.style.animationDuration = duration/1000 + "s";
    
        e.target.append(rippleElement);
        setTimeout(() => rippleElement.remove(), duration);
      }

    return (
        <div className="ripple-effect" onClick={createRipple}>
            {children}
        </div>
    )
}