import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RecetteByCategoryComponent } from './recette-by-category.component';

describe('RecetteByCategoryComponent', () => {
  let component: RecetteByCategoryComponent;
  let fixture: ComponentFixture<RecetteByCategoryComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [RecetteByCategoryComponent]
    });
    fixture = TestBed.createComponent(RecetteByCategoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
