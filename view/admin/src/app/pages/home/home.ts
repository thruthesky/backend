import { Component, OnInit } from '@angular/core';

@Component({
    moduleId: module.id,
    selector: 'home-page',
    templateUrl: 'home.html'
})
export class HomePage implements OnInit {
    constructor() {

// the array to be sorted
var list = ['Delta', 'alpha', 'CHARLIE', 'bravo'];

// temporary array holds objects with position and sort-value
var mapped = list.map(function(el, i) {
  return { index: i, value: el.toLowerCase() };
})

// sorting the mapped array containing the reduced values
mapped.sort(function(a, b) {
  return +(a.value > b.value) || +(a.value === b.value) - 1;
});

// container for the resulting order
var result = mapped.map(function(el){
  return list[el.index];
});

console.log(result);


     }

    ngOnInit() { }
}

