import {Component} from '@angular/core';
// import thư viện của Component từ thư viện Angular core

@Component({
    templateUrl: './word/word.component.html',
    selector: 'app-word',
    styleUrls: [`./word/word.component.css`]
})
export class WordComponent {
    en = 'Hello';
    vn = 'Xin chào';
}
